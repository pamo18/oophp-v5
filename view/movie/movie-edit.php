<h4 class="center" style="text-decoration: underline;">Edit Movie</h4>
<form class="form" method="post">
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
        <input class="button invert wide-button" type="submit" name="doSave" value="Save">
        <input class="button invert wide-button" type="reset" value="Reset">
    </p>
    <p>
        <a class="button wide-button" href="movie-select">Select movie</a>
        <a class="button wide-button" href="show-all">Show all</a>
    </p>
</form>
