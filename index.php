<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>
<body>

<table width="600">
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

        <tr>
            <td width="20%">Select file</td>
            <td width="80%"><input type="file" name="file" id="file" /></td>
        </tr>

        <tr>
            <td>Submit</td>
            <td><input type="submit" name="submit" /></td>
        </tr>

    </form>
</table>

<?php
if ( isset($_POST["submit"]) ) {
    if (!empty($_FILES['file']['tmp_name'])) {
        require_once ('CsvData.php');
        $csvData = new CsvData($_FILES['file']['tmp_name']);

?>
<div id="app">
    <div v-for="item in items" style="margin-top: 20px; margin-bottom: 30px;">
    As a result report for Customer ID {{ item.id }} we have:<br>
    ➔ ​ Number of customer's calls within same continent ​ : ​ {{ item.same_count }}<br>
    ➔ ​ Total duration of customer's calls within same continent ​ : ​ {{ item.same_duration }} seconds<br>
    ➔ ​ Number of all customer's calls ​ : ​ {{ item.all_count }}<br>
    ➔ ​ Total duration of all customer's calls ​ : ​{{ item.all_duration }} seconds<br>
    </div>
</div>
<script>
    new Vue({
        el: '#app',
        data: {
            items: [
                <? foreach ($csvData->arResult as $k => $item):?>
                {   id: '<?= $k; ?>',
                    same_count: '<?= $item['SAME_COUNT']; ?>',
                    same_duration: '<?= $item['SAME_DURATION']; ?>',
                    all_count: '<?= $item['ALL_COUNT']; ?>',
                    all_duration: '<?= $item['ALL_DURATION']; ?>',
                },

                <? endforeach; ?>
            ]
        }
    })
</script>
<?
    }
}
?>

</body>
</html>
