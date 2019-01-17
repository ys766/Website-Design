<header>
  <h1 id="WebsiteHeader"> TRAVEL BOOK </h1>
  <nav id="menu">
    <ul>
    <?php
    foreach($pages as $page_id => $page_name) {
      $filename = $page_id . ".php";

      if ($page_id == "private_gallery") {
        if (!$current_user) {
          $new_page_name = "Log In";
        }
        else {
          $new_page_name = "My Gallery";
        }
      }
      else {
        $new_page_name = $page_name;
      }
      if ($current_page == $page_id) {
        echo "<li> <a href=" . $filename . " class='current_page'>" . $new_page_name . "</a></li>";
      }
      else {
        echo "<li> <a href=" . $filename . ">" . $new_page_name . "</a></li>";
      }
    }
    ?>
  </ul>
  </nav>
  <?php
  if ($current_user) {
    echo "<h2> Hello " . $current_user["realname"] . "! </h2>";
  }
   ?>
</header>
