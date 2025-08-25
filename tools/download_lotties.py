from __future__ import annotations
import argparse
from pathlib import Path
from typing import List, Optional, Sequence, Tuple
from datetime import datetime
import sys
import importlib.util
import types

GRAPHQL_ENDPOINT = "https://graphql.lottiefiles.com/2022-08"
GRAPHQL_QUERY = (
    """
    query($q: String!, $limit: Int!, $orderBy: QuerySortOptions) {
        searchPublicAnimations(query: $q, first: $limit, orderBy: $orderBy) {
            edges {
                node {
                    id
                    name
                    jsonUrl
                    downloads
                    likesCount
                    lottieFileSize
                    publishedAt
                }
            }
        }
    }
    """
)
GRAPHQL_COUNT_QUERY = (
    """
    query($q: String!, $limit: Int!, $orderBy: QuerySortOptions) {
        searchPublicAnimations(query: $q, first: $limit, orderBy: $orderBy) {
            totalCount
        }
    }
    """
)

def download_lotties(
    search_term: str,
    dest_dir: str = ".",
    limit: int = 3,
    *,
    order_by: Optional[str] = "POPULARITY",
    min_downloads: int = 0,
    sort_by: str = "likes",
    min_size: int = 0,
    search_limit: Optional[int] = None,
    prefer_new: bool = False,
) -> List[Path]:
    """Download Lottie JSON files for ``search_term``.
    Parameters
    ----------
    search_term: str
        Term to search for on LottieFiles.
    dest_dir: str
        Directory where the downloaded files will be saved. Created if missing.
    limit: int
        Maximum number of Lottie files to download. Defaults to 3.
    order_by: Optional[str]
        Sorting option accepted by the API (e.g. ``"POPULARITY"``).
    min_downloads: int
        Only download animations with at least this many downloads.
    sort_by: str
        Local sorting key to pick top results. ``"likes"`` or ``"downloads"``.
    min_size: int
        Only download animations whose Lottie file size in bytes is greater
        than or equal to this value.
    search_limit: Optional[int]
        Number of results to request from the API before filtering. Defaults
        to ``max(limit * 5, 10)``.
    Returns
    -------
    List[pathlib.Path]
        Paths to the downloaded files.
    """
    dest = Path(dest_dir)
    dest.mkdir(parents=True, exist_ok=True)
    if search_limit is None:
        search_limit = max(limit * 5, 10)
    if prefer_new and order_by == "POPULARITY":
        variables = {"q": search_term, "limit": search_limit, "orderBy": "NEWEST"}
    else:
        variables = {"q": search_term, "limit": search_limit, "orderBy": order_by}
    req = _load_requests()
    response = req.post(
        GRAPHQL_ENDPOINT, json={"query": GRAPHQL_QUERY, "variables": variables}, timeout=10
    )
    response.raise_for_status()
    data = response.json()
    edges = data.get("data", {}).get("searchPublicAnimations", {}).get("edges", [])
    key_map = {"likes": "likesCount", "downloads": "downloads"}
    sort_key = key_map.get(sort_by, "likesCount")
    if prefer_new:
        edges.sort(
            key=lambda e: (
                e.get("node", {}).get(sort_key, 0),
                e.get("node", {}).get("publishedAt", ""),
            ),
            reverse=True,
        )
    else:
        edges.sort(key=lambda e: e.get("node", {}).get(sort_key, 0), reverse=True)
    downloaded: List[Path] = []
    for edge in edges:
        node = edge.get("node", {})
        json_url = node.get("jsonUrl")
        if not json_url:
            continue
        if node.get("downloads", 0) < min_downloads:
            continue
        if node.get("lottieFileSize") and node.get("lottieFileSize") < min_size:
            continue
        # Create a safe filename using animation name and id
        name_part = node.get("name") or "lottie"
        safe_name = "".join(c if c.isalnum() or c in ("_", "-") else "_" for c in name_part)
        filename = f"{safe_name}_{node.get('id')}.json"
        file_path = dest / filename
        file_resp = req.get(json_url, timeout=10)
        file_resp.raise_for_status()
        with open(file_path, "wb") as f:
            f.write(file_resp.content)
        downloaded.append(file_path)
        if len(downloaded) >= limit:
            break
    return downloaded

def _load_requests():
    """Return the real ``requests`` module even if a test replaced it."""
    req = sys.modules.get("requests")
    if isinstance(req, types.SimpleNamespace) or not hasattr(req, "post"):
        spec = importlib.util.find_spec("requests")
        if not spec or not spec.loader:
            raise ImportError("requests module not available")
        module = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(module)
        sys.modules["requests"] = module
        req = module
    return req

def rank_search_terms(search_terms: Sequence[str], *, order_by: Optional[str] = "POPULARITY") -> List[Tuple[str, int]]:
    """Return ``search_terms`` sorted by popularity based on result counts."""
    req = _load_requests()
    ranked: List[Tuple[str, int]] = []
    for term in search_terms:
        variables = {"q": term, "limit": 1, "orderBy": order_by}
        response = req.post(
            GRAPHQL_ENDPOINT,
            json={"query": GRAPHQL_COUNT_QUERY, "variables": variables},
            timeout=10,
        )
        response.raise_for_status()
        data = response.json()
        count = (
            data.get("data", {})
            .get("searchPublicAnimations", {})
            .get("totalCount", 0)
        )
        ranked.append((term, count))
    ranked.sort(key=lambda x: x[1], reverse=True)
    return ranked

def main() -> None:
    parser = argparse.ArgumentParser(description="Download Lottie animations from LottieFiles")
    parser.add_argument(
        "search_terms",
        nargs="+",
        help="Search terms to look up. Use commas to combine keywords for a single query",
    )
    parser.add_argument(
        "-o",
        "--output",
        default=".",
        help="Directory to place downloaded files (default: current directory)",
    )
    parser.add_argument(
        "-n",
        "--number",
        type=int,
        default=3,
        help="Number of files to download per term (default: 3)",
    )
    parser.add_argument(
        "--order",
        default="POPULARITY",
        help="Sorting option accepted by the API (default: POPULARITY)",
    )
    parser.add_argument(
        "--min-downloads",
        type=int,
        default=0,
        help="Minimum number of downloads required",
    )
    parser.add_argument(
        "--sort-by",
        choices=["likes", "downloads"],
        default="likes",
        help="Sort results locally by likes or downloads (default: likes)",
    )
    parser.add_argument(
        "--min-size", type=int, default=0, help="Minimum lottie file size in bytes"
    )
    parser.add_argument(
        "--search-limit",
        type=int,
        default=None,
        help="Number of results to request before filtering",
    )
    parser.add_argument(
        "--prefer-new",
        action="store_true",
        help="Prefer newer animations when sorting",
    )
    parser.add_argument(
        "--log-file",
        default=None,
        help="Optional path to append a download log",
    )
    args = parser.parse_args()
    # Allow comma-separated keywords per term
    processed_terms = [" ".join(t.split(",")) for t in args.search_terms]
    logf = open(args.log_file, "a") if args.log_file else None
    if logf:
        logf.write(f"# Run {datetime.now().isoformat()}\n")
    ranked = rank_search_terms(processed_terms, order_by=args.order)
    for term, count in ranked:
        print(f"{term}: {count} results")
        if logf:
            logf.write(f"TERM {term}: {count} results\n")
        paths = download_lotties(
            term,
            dest_dir=args.output,
            limit=args.number,
            order_by=args.order,
            min_downloads=args.min_downloads,
            sort_by=args.sort_by,
            min_size=args.min_size,
            search_limit=args.search_limit,
            prefer_new=args.prefer_new,
        )
        for path in paths:
            print(path)
            if logf:
                logf.write(f"  {path}\n")
    if logf:
        logf.close()

if __name__ == "__main__":
    main()
