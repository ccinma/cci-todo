<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Style -->
  @yield("style")

  <!-- Title -->
  <title>@yield("title")</title>
</head>
<body>

  <!-- Content -->
  <main>
    @yield("content")
  </main>

  <!-- Script -->
  @yield("script")
</body>
</html>
