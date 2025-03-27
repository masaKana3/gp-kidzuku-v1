<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
</head>
<body>
    <h1>商品登録フォーム</h1>

    <form action="{{ url('/products') }}" method="POST">
        @csrf
        <label>商品名:</label>
        <input type="text" name="name" required><br>

        <label>説明:</label>
        <textarea name="description"></textarea><br>

        <label>価格:</label>
        <input type="number" name="price" required><br>

        <button type="submit">登録</button>
    </form>
</body>
</html>
