{*
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is:  vtiger CRM Open Source
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *
 ********************************************************************************/
*}

<script type="text/javascript" src="{"modules/Settings/resources/QlikIframe.js"|resourcever}"></script>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td valign="top"></td>
            <td class="showPanelBg" style="padding: 5px;" valign="top" width="100%">
                <div align=center>
                    {include file='SetMenu.tpl'}
                    {include file='Buttons_List.tpl'} {* crmv@30683 *}
                    <!-- DISPLAY -->
                    <table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
                        <tr>
                            <td width=50 rowspan=2 valign=top><img src="{'vteQlik50x50_Color.png'|resourcever}"
                                    alt="{$MOD.LBL_QLIKIFRAME_CONF}" width="48" height="48" border=0
                                    title="{$MOD.LBL_QLIKIFRAME_CONF}"></td>
                            <td class=heading2 valign=bottom><b> {$MOD.LBL_SETTINGS} > Qlik For VTE</b>
                            </td>
                            <!-- crmv@30683 -->
                        </tr>
                        <tr>
                            <td valign=top class="small">{$MOD.LBL_QLIKIFRAME_CONF_DESCRIPTION}</td>
                        </tr>
                    </table>

                    <br>
                    <table border=0 cellspacing=0 cellpadding=10 width=100%>

                        <tr>
                            <td>
                                <table border=0 cellspacing=0 cellpadding=2 width=100% class="tableHeading">
                                    <tr>
                                        <td class="big" width="70%"><strong>{$MOD.LBL_CONFIGURATION}</strong></td>
                                        <td width="30%" nowrap align="right">
                                            <a
                                                href="index.php?module=Settings&action=QlikIframe&parenttab=Settings&mode=edit&qlikconf="><img
                                                    src="{'btnL3Add.gif'|resourcever}" border="0" /></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        {foreach item=CONF from=$CONFS}
                            {assign var="CONFINFO" value=$CONF->getAsMap()}
                            <tr>
                                <td>

                                    <form action="index.php" method="post" id="form" onsubmit="VtigerJS_DialogBox.block();">
                                        <input type="hidden" name="__csrf_token" value="{$CSRF_TOKEN}"> {* crmv@171581 *}
                                        <input type='hidden' name='module' value='Settings'>
                                        <input type='hidden' name='action' value='QlikIframe'>
                                        <input type='hidden' name='mode' value='edit'>
                                        <input type='hidden' name='confname' value='{$CONFINFO.confname}'>
                                        <input type='hidden' name='return_action' value='QlikIframe'>
                                        <input type='hidden' name='return_module' value='Settings'>
                                        <input type='hidden' name='parenttab' value='Settings'>

                                        {* When mode is Ajax, xmode will be set *}
                                        <input type='hidden' name='xmode' value=''>
                                        <input type='hidden' name='file' value=''>

                                        <table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
                                            <tr>
                                                <td class="big" width="70%"><strong>{$CONFINFO.confname}
                                                        {$MOD.LBL_INFORMATION}</strong></td>
                                                <td width="30%" nowrap align="right">

                                                    <input type="submit" class="crmbutton small create"
                                                        value="{$APP.LBL_EDIT}" />

                                                    <input type="submit" class="crmbutton small delete"
                                                        onclick="if(confirm(alert_arr.ARE_YOU_SURE)){ldelim}with(this.form) {ldelim}action.value='SettingsAjax';file.value='QlikIframe';mode.value='Ajax';xmode.value='remove';{rdelim}{rdelim}else return false;"
                                                        value="{$MOD.LBL_DELETE}" />
                                                </td>
                                            </tr>
                                        </table>

                                        <table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
                                            <tr>
                                                <td class="small" valign=top>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>{$MOD.LBL_CONF} {$MOD.LBL_NAME}</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">
                                                                {$CONFINFO.confname}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>QRSurl
                                                                    {$MOD.LBL_NAME}</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">{$CONFINFO.QRSurl}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>{$MOD.LBL_QLIKIFRAME_CONF_ENDPOIT}
                                                                    {$MOD.LBL_NAME}</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">{$CONFINFO.endpoint}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>QRSCertfile</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">{$CONFINFO.QRSCertfile}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>QRSCertkeyfile</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">
                                                                {$CONFINFO.QRSCertkeyfile}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>QRSCertkeyfilePassword</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">
                                                                {$CONFINFO.QRSCertkeyfilePassword}
                                                            </td>
                                                        </tr>



                                                        <tr>
                                                            <td width="20%" nowrap class="small cellLabel">
                                                                <strong>{$MOD.LBL_STATUS}</strong>
                                                            </td>
                                                            <td width="80%" class="small cellText">
                                                                {if $CONFINFO.isValid eq '1'}<font color=green>
                                                                        <b>{$MOD.LBL_ENABLED}</b>
                                                                    </font>
                                                                {elseif $CONFINFO.isValid eq '0'}<font color=red>
                                                                        <b>{$MOD.LBL_DISABLED}</b>
                                                                </font>{/if}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </form>

                                </td>
                            </tr>

                        {/foreach}

                    </table>

            </td>
        </tr>
</table>
</td>
</tr>
</table>

</div>

</td>
<td valign="top"></td>
</tr>
</tbody>
</form>
</table>

</tr>
</table>

</tr>
</table>