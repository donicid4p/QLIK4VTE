#!/bin/bash

# Chiedi all'utente di inserire la root directory
echo "Root directory:"
read -r input_string

root_dir="$input_string"

echo "Root directory: $root_dir"


 #se il file esiste
if [ -f "$root_dir/themes/images/QlikIframe.php" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/QlikIframe.php"
fi
if [ -f "$root_dir/themes/images/logo_qlikvte.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/logo_qlikvte.png"
fi
if [ -f "$root_dir/themes/images/vteQlik20x20.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik20x20.png"
fi
if [ -f "$root_dir/themes/images/vteQlik50x50_Color.png" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik50x50_Color.png"
fi
if [ -f "$root_dir/themes/images/vteQlik50x50.pngg" ]; then
        #rimuovilo
        rm "$root_dir/themes/images/vteQlik50x50.png"
fi
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




if [ -f "$root_dir/themes/images/DetailViewUI.old.tpl" ]; then
        #rimetti l'originale
        mv "$root_dir/Smarty/templates/DetailViewUI.old.tpl" "$root_dir/Smarty/templates/DetailViewUI.tpl"
fi

if [ -f "$root_dir/themes/images/EditViewUI.old.tpl" ]; then
        #rimetti l'originale
        mv "$root_dir/Smarty/templates/EditViewUI.old.tpl" "$root_dir/Smarty/templates/EditViewUI.tpl"
fi


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
