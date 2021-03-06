<h4 class="center" style="text-decoration: underline;">Manage Movie</h4>
<form class="form" method="post">
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
        <input class="button invert wide-button" type="submit" name="doAdd" value="Add">
        <input class="button invert wide-button" type="submit" name="doEdit" value="Edit">
        <input class="button invert wide-button" type="submit" name="doDelete" value="Delete">
    </p>
    <p><a class="button wide-button" href="show-all">Show all</a></p>
    </fieldset>
</form>
