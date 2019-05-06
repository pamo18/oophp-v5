<h4 class="center" style="text-decoration: underline;">Edit Movie</h4>
<form class="movie-form" method="post">
    <input type="hidden" name="movieId" value="<?= $movie->id ?>"/>

    <p>
        <label>Title:<br>
        <input type="text" name="movieTitle" value="<?= $movie->title ?>"/>
        </label>
    </p>

    <p>
        <label>Year:<br>
        <input type="number" name="movieYear" value="<?= $movie->year ?>"/>
    </p>

    <p>
        <label>Image:<br>
        <input type="text" name="movieImage" value="<?= $movie->image ?>"/>
        </label>
    </p>

    <p>
        <input class="button invert wide" type="submit" name="doSave" value="Save">
        <input class="button invert wide" type="reset" value="Reset">
    </p>
    <p>
        <a class="button wide" href="movie-select">Select movie</a>
        <a class="button wide" href="show-all">Show all</a>
    </p>
</form>
