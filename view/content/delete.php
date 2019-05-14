<h4 class="center" style="text-decoration: underline;">Delete Content?</h4>
<form class="form" method="post">
    <input type="hidden" name="contentId" value="<?= $id ?>"/>

    <p>
        <label>Title:<br>
            <input type="text" name="contentTitle" value="<?= $title ?>" readonly/>
        </label>
    </p>

    <p>
        <input class="button invert wide-button" type="submit" name="doDelete" value="Delete"></input>
    </p>
</form>
