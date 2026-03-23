<?php
if (!is_dir('storage/app/public/img')) {
    mkdir('storage/app/public/img', 0755, true);
}
file_put_contents('storage/app/public/img/placeholder.jpg', file_get_contents('https://ui-avatars.com/api/?name=Image+Coming+Soon&size=512&background=0D8ABC&color=fff'));
echo "Placeholder generated.\n";
