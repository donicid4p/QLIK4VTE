#!/bin/bash

# Chiedi all'utente di inserire la root directory
echo "Root directory:"
read -r input_string

root_dir="$input_string"

echo "Root directory: $root_dir"

#array con i nomi dei file da rimuovere
declare -a files=("QlikIframe.php" "logo_qlikvte.png" "vteQlik20x20.png" "vteQlik50x50_Color.png" "vteQlik50x50.png")
#per ogni file nell'array
for file in "${files[@]}"; do
    #se il file esiste
    if [ -f "$root_dir/themes/images/$file" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/$file"
    fi
done

# Ripristina il file originale style.css se esiste
if [ -f "$root_dir/themes/next22/style.css.old" ]; then
    mv "$root_dir/themes/next22/style.css.old" "$root_dir/themes/next22/style.css"
fi

# Rimuovi il file style.css copiato precedentemente
#rm "$root_dir/themes/next22/style.css"

# Ripristina il file originale theme.php se esiste
if [ -f "$root_dir/themes/next22/theme.php.old" ]; then
    mv "$root_dir/themes/next22/theme.php.old" "$root_dir/themes/next22/theme.php"
fi

# Rimuovi il file theme.php copiato precedentemente
#rm "$root_dir/themes/next22/theme.php"
#array con i nomi dei file da rimuovere
declare -a files=("DetailViewUI" "EditViewUI")
#per ogni file nell'array
for file in "${files[@]}"; do
    #se il file esiste
    if [ -f "$root_dir/themes/images/$file" ]; then
        #rimetti l'originale
        mv "$root_dir/Smarty/templates/$file.old.tpl" "$root_dir/Smarty/templates/$file.tpl"
    fi
done

# Rimuovi la cartella QlikProxy da root_dir/
rm -r "$root_dir/QlikProxy"


rm -r "$root_dir/modules/QlikIframe"


rm -r "$root_dir/modules/Settings/QlikIframe"


rm -r "$root_dir/Smarty/templates/QlikIframe"

# Rimuovi la cartella qlik da root_dir/modules/SDK
rm -r "$root_dir/modules/SDK/qlik"

# Rimuovi il file QlikIframe.php da root_dir/modules/Settings
rm "$root_dir/modules/Settings/QlikIframe.php"

# Rimuovi il file QlikIframe.js da root_dir/modules/Settings/resources
rm "$root_dir/modules/Settings/resources/QlikIframe.js"
