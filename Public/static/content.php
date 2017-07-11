<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/css/typo.css">
    <link rel="stylesheet" href="/static/plugins/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/static/plugins/highlight/styles/darcula.css">
    <!--highlight-->
    <script src="/static/plugins/jquery/jquery-1.11.3.min.js"></script>
    <script src="/static/plugins/marked/marked.js"></script>
    <script src="/static/plugins/highlight/highlight.pack.js"></script>
    <script src="/static/plugins/epiceditor/js/epiceditor.min.js"></script>
</head>
<body class="typo">
<div id="content_html" style="margin-left: 50px;margin-right: 50px;"><?php echo $content; ?></div>

<script>
    $(function () {
        $('pre code').each(function (i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
</body>
</html>