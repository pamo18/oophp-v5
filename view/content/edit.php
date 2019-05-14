<h4 class="center" style="text-decoration: underline;">Edit Content</h4>
<form class="form wide-form" method="post">
    <input type="hidden" name="contentId" value="<?= $id ?>"/>

    <p>
        <label>Title:<br>
        <input type="text" name="contentTitle" value="<?= $title ?>"/>
        </label>
    </p>

    <p>
        <label>Path:<br>
        <input type="text" name="contentPath" value="<?= $path ?>"/>
    </p>

    <p>
        <label>Slug:<br>
        <input type="text" name="contentSlug" placeholder="Auto generated if empty" value="<?= $slug ?>"/>
    </p>

    <p>
        <label>Text:<br>
        <textarea class="tall-text" name="contentData"><?= $data ?></textarea>
    </p>

    <p>
        <label>Type:<br>
            <select name="contentType">
                <option selected disabled>Choose type here</option>
                <option type="text"
                <?php if ($type == "page") : ?>
                    selected
                <?php endif; ?> value="page"/>Page</option>
                <option type="text"
                <?php if ($type == "post") : ?>
                    selected
                <?php endif; ?> value="post"/>Post</option>
            </select>
    </p>

     <p>
         <label>Filters:<br>
         <div>
             <label><input class="check" type="checkbox" name="contentFilter[]"
                <?php if (strstr($filter, "bbcode")) : ?>
                    checked
                <?php endif; ?> value="bbcode"/>bbcode</label><br>
             <label><input class="check" type="checkbox" name="contentFilter[]"
                <?php if (strstr($filter, "link")) : ?>
                    checked
                <?php endif; ?> value="link"/>link</label><br>
             <label><input class="check" type="checkbox" name="contentFilter[]"
                <?php if (strstr($filter, "markdown")) : ?>
                    checked
                <?php endif; ?> value="markdown"/>markdown</label><br>
             <label><input class="check" type="checkbox" name="contentFilter[]"
                <?php if (strstr($filter, "nl2br")) : ?>
                    checked
                <?php endif; ?>value="nl2br"/>nl2br</label>
        </div>
     </p>

        <p>
            <label><?= ($published ? "Published" : "Publish:") ?><input id="check" class="check" type="checkbox"
                <?php if ($published) : ?>
                    disabled checked
                <?php endif; ?> name="doPublish"/><br>
                <input id="publish" type="datetime" name="contentPublish" placeholder="Check above for current date & time"
                <?php if ($published) : ?>
                    readonly checked
                <?php endif; ?> value="<?= $published ?>"/>
        </p>

    <p>
        <input class="button invert wide-button" type="submit" name="doSave" value="Save"></input>
        <button class="button invert wide-button" type="reset"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>
        <input class="button invert wide-button" type="submit" name="doDelete" value="Delete"></input>
    </p>
</form>
