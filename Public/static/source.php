<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/static/plugins/editormd/css/editormd.min.css" />
    <script src="/static/plugins/jquery/jquery-1.11.3.min.js"></script>
	<script src="/static/plugins/editormd/editormd.min.js"></script>
</head>
<body class="typo">

<div id="editormd">
    <textarea style="display:none;"><?php echo $source; ?></textarea>
</div>

<script type="text/javascript">
    var editor = editormd("editormd", {
        path : "/static/plugins/editormd/lib/" // Autoload modules mode, codemirror, marked... dependents libs path
    });

    var getMarkdown = function () {
        return editor.getMarkdown()
    };




        /*
        // or
        var editor = editormd({
            id   : "editormd",
            path : "../lib/"
        });
        */

</script>

</body>
</html>