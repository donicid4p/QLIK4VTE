#!/bin/bash

# Chiedi all'utente di inserire la root directory
echo "Root directory:"
read -r input_string

root_dir="$input_string"

echo "Root directory: $root_dir"



# tutti i file di tipo png li copio nella cartella root_dir/themes/images
find . -name "*.png" -exec cp {} "$root_dir/themes/images" \;

# se il file root_dir/themes/next22/style.css esiste, allora lo rinomino in style.css.old
if [ -f "$root_dir/themes/next22/style.css" ]; then
    mv "$root_dir/themes/next22/style.css" "$root_dir/themes/next22/style.css.old"
fi

# copio il file style.css in root_dir/themes/next22/style.css
cp style.css "$root_dir/themes/next22/style.css"

# stesso procedimento per il file theme.php
if [ -f "$root_dir/themes/next22/theme.php" ]; then
    mv "$root_dir/themes/next22/theme.php" "$root_dir/themes/next22/theme.php.old"
fi

cp theme.php "$root_dir/themes/next22/theme.php"

# per ogni file .tpl, verifico che esista in root_dir/Smarty/templates e se esiste lo rinomino in .tpl.old
for file in *.tpl; do
    if [ -f "$root_dir/Smarty/templates/$file" ]; then
        mv "$root_dir/Smarty/templates/$file" "$root_dir/Smarty/templates/$file.old"
    fi
    cp "$file" "$root_dir/Smarty/templates/$file"
done

# la cartella QlikProxy la copio in root_dir/
cp -r QlikProxy "$root_dir/"

# rinomina la dir QlikIframe_mod in QlikIframe e copiala in root_dir/modules
mv -r QlikIframe_mod QlikIframe
cp -r QlikIframe "$root_dir/modules/"
mv -r QlikIframe_mod QlikIframe

mv-r  QLikIframe_settings QlikIframe
cp -r QlikIframe "$root_dir/modules/Settings/"
mv -r QlikIframe_settings QlikIframe

mv -r QLikIframe_tpl QlikIframe
cp -r QlikIframe "$root_dir/Smarty/templates/"
mv -r QlikIframe_tpl QlikIframe

cp -r qlik "$root_dir/modules/SDK/"

cp QlikIframe.php "$root_dir/modules/Settings/"

cp QlikIframe.js "$root_dir/modules/Settings/resources/QlikIframe.js"

