<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <button id="btn">点击</button>
</body>
</html>

<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    $('#btn').click(function(){
        $.ajax({
            url:'http://api.1809a.com/test/demo/',
            dataType:'json',
            success:function(){
                alert('ok');
            }
        })
    })

</script>
