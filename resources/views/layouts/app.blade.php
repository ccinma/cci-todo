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

  @include("layouts.components.header")

  <!-- Content -->
  <main>
    @yield("container")
  </main>

  @include("layouts.components.footer")

  <!-- Script -->
  @yield("script")
</body>
</html>
