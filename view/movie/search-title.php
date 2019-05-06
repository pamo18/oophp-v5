<h4 class="center" style="text-decoration: underline;">Search movie by title</h4>
<form class="movie-form" method="get">
    <input type="hidden" name="route" value="search-title">
    <p>
        <label>Titel:
            <input type="search" name="searchTitle" placeholder="Use % as wildcard" value="<?= esc($searchTitle) ?>"/>
        </label>
    </p>
    <p>
        <input class="button invert wide" type="submit" name="doSearch" value="Search">
    </p>
    <p><a class="button wide" href="show-all">Show all</a></p>
</form>
