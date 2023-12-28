

window.VTE = window.VTE || {};

VTE.Settings = VTE.Settings || {};

VTE.Settings.QlikIframe = VTE.Settings.QlikIframe || {


    validateEditForm: function (form) {
        confs = [];
        jQuery.ajax({
            url: "index.php?module=SDK&action=SDKAjax&file=qlik/src/QlikConfNames", async: false,
            success: function (result) {
                //console.log(result);
                //riempi confs con i risultati di result
                confs = result;


            }
        });
        if (!emptyCheck('confname', alert_arr.LBL_NAME, 'text')) {

            return false;
        }

        //get confname input value
        var confname = form.confname.value;
        var savemode = form.savemode.value;
        var oldconfname = form.hidden_confname.value;

        //if confname is in confs array, return false

        if ((confs.includes(confname) && (savemode == "save" || savemode == "")) || (savemode == "edit" && confs.includes(confname) && confname != oldconfname)) {
            alert("Confname already exists");
            return false;
        }




        return true;
    },



};


var QlikIframeBox = QlikIframeBox || {



    validateAndSave: function () {
        var me = this;
        me.prepareFieldForSave();
        if (!me.validate()) return false;
        return true;
    },

    prepareFieldForSave: function () {

    },

    validate: function () {
        return true;
    },

};
