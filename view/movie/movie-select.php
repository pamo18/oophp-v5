<h4 class="center" style="text-decoration: underline;">Select Movie</h4>
<form class="movie-form" method="post">
    <p>
        <label>Movie:<br>
        <select name="movieId">
            <option value="">Select movie...</option>
            <?php foreach ($movies as $movie) : ?>
            <option value="<?= $movie->id ?>"><?= $movie->title ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    </p>

    <p>
        <input class="button invert wide" type="submit" name="doAdd" value="Add">
        <input class="button invert wide" type="submit" name="doEdit" value="Edit">
        <input class="button invert wide" type="submit" name="doDelete" value="Delete">
    </p>
    <p><a class="button wide" href="show-all">Show all</a></p>
    </fieldset>
</form>
