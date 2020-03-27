<?php
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Swimset</title>
    <link rel="stylesheet" href="css/main.css" />
    <link
      href="https://fonts.googleapis.com/css?family=Roboto&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <header class="relative flex-grow overflow-y-hidden">
      <div class="flex bg-gray-200 p-05">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
        >
          <path
            d="M2.479 18c.978 0 1.309-.524 1.708-.922.813-.816 1.813-.469 1.813.847v6.075h6.075c1.315 0 1.663-1 .847-1.813-.398-.399-.922-.73-.922-1.708 0-1.087 1.108-2.479 3-2.479s3 1.392 3 2.479c0 .978-.524 1.309-.922 1.708-.816.813-.469 1.813.847 1.813h6.075v-6.075c0-1.315-1-1.663-1.813-.847-.399.398-.73.922-1.708.922-1.087 0-2.479-1.108-2.479-3s1.392-3 2.479-3c.978 0 1.309.524 1.708.922.813.816 1.813.469 1.813-.847v-6.075h-6.075c-1.315 0-1.663-1-.847-1.813.398-.399.922-.73.922-1.708 0-1.087-1.108-2.479-3-2.479s-3 1.392-3 2.479c0 .978.524 1.309.922 1.708.816.813.469 1.813-.847 1.813h-6.075v6.075c0 1.315-1 1.663-1.813.847-.399-.398-.73-.922-1.708-.922-1.087 0-2.479 1.108-2.479 3s1.392 3 2.479 3z"
          />
        </svg>
        <input class="flex-grow mx-05" type="text" name="searchbar" />
        <nav class="flex items-center">
          <ul class="flex">
            <li class="mx-05" href="#">
              Home
            </li>
            <li class="mx-05">
              <a href="./public/sprites.html">
                Sprite Sheets
              </a>
            </li>
            <li class="mx-05" href="#">
              3D Models
            </li>
            <li class="mx-05" href="#">
              2D Models
            </li>
          </ul>
        </nav>
        <button>
          Login
        </button>
        <button>
          Sign up
        </button>
      </div>
    </header>
    <main></main>
  </body>
</html>