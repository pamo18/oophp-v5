<h4 class="center" style="text-decoration: underline;">Search movie by year</h4>
<form class="movie-form" method="get">
    <input type="hidden" name="route" value="search-year">
    <p>
        <label>From:
        <input type="number" name="year1" value="<?= $year1 ?: 1900 ?>" min="1900" max="2100"/>
        <label>To:
        <input type="number" name="year2" value="<?= $year2  ?: 2100 ?>" min="1900" max="2100"/>
        </label>
    </p>
    <p>
        <input class="button invert wide" type="submit" name="doSearch" value="Search">
    </p>
    <p><a class="button wide" href="show-all">Show all</a></p>
</form>
