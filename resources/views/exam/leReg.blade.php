<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h3>注册</h3><hr/>
    <form action="/legal/regDo/" method='post'>
        @csrf
        名称：<input type="text" name='le_name'><br>
        负责人：<input type="text" name='le_person'><br>
        住所：<input type="text" name='le_address'><br>
        注册类型：<input type="text" name='le_type'><br>

        <input type="submit" value="注册">
    </form>
</body>
</html>