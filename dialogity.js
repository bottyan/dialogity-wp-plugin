/*
Dialogity Website Chat WordPress plugin
Copyright (C) 2020, Dialogity.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// variable to store the loaded code snippet
var dialogity_script_previe_cache = "";
// callback function for the loaded script
var dialogity_setScriptPreview = function(data) {
    dialogity_script_previe_cache = data;
    dialogity_updateScriptPreview();
}
// adding the custom script, if any, and filling the preview, the preview will be saved to the DB
var dialogity_updateScriptPreview = function() {
    if (dialogity_script_previe_cache==="") return;
    var toInsert = dialogity_script_previe_cache;
    var customScript = jQuery("#dialogity_field_custom_script").val()
    if (customScript.trim() === "") {
        toInsert = toInsert.replace("//LNG", "window._chb_lang_code = 'ENG';");
    } else {
        toInsert = toInsert.replace("//LNG", customScript);
    }
    jQuery("#dialogity_field_script_preview").val(toInsert);
}
// loading the code snippet from the server
var dialogity_getCode = function() {
    var uuid = jQuery("#dialogity_field_uuid").val()
    if (uuid !== "") {
        jQuery.getJSON( "https://api2.dialogity.com/api/get-code-snippet/"+uuid, function( data ) {
            dialogity_setScriptPreview(data);
            jQuery("#uuid_error_msg").html("");
        }).fail(function() {
            jQuery("#uuid_error_msg").html("Invalid UUID");
            jQuery("#dialogity_field_script_preview").val("");
        });
    }
}
dialogity_getCode();
// loading every time the user id is changing
jQuery("#dialogity_field_uuid").on('input', function(){
    dialogity_getCode();
});
// refreshing the script every time the custom script is changing
jQuery("#dialogity_field_custom_script").on('input', function(){
    dialogity_updateScriptPreview();
});