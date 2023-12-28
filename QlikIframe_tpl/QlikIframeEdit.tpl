<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script type="text/javascript" src="{"modules/Settings/resources/QlikIframe.js"|resourcever}"></script>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">

	<tbody>
		<tr>
			<td valign="top"></td>
			<td class="showPanelBg" style="padding: 5px;" valign="top" width="100%">

				<form action="index.php" method="post" id="form" enctype="multipart/form-data"
					onsubmit="VtigerJS_DialogBox.block();return QlikIframeBox.validateAndSave();">
					<input type='hidden' name='module' value='Settings'>
					<input type='hidden' name='action' value='QlikIframe'>
					<input type='hidden' name='mode' value='save'>
					<input type='hidden' name='return_action' value='QlikIframe'>
					<input type='hidden' name='return_module' value='Settings'>
					<input type='hidden' name='parenttab' value='Settings'>
					<input type='hidden' name='file' value='QlikIframe'>
					<input type='hidden' name='savemode' value='{$SAVEMODE}'>
					<input type='hidden' id='conf_id' name='conf_id' value='{$CONFINFO.conf_id}'>

					<div align=center>

						<!-- DISPLAY -->
						<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
							<tr>
								<td width=50 rowspan=2 valign=top><img src="{'vteQlik50x50_Color.png'|resourcever}"
										alt="{$MOD.LBL_MAIL_SCANNER}" width="48" height="48" border=0
										title="{$MOD.LBL_MAIL_SCANNER}"></td>
								<td class=heading2 valign=bottom><b>{$MOD.LBL_SETTINGS} > Qlik For VTE</b>
								</td> <!-- crmv@30683 -->
							</tr>
							<tr>
								<td valign=top class="small">{$MOD.LBL_QLIKIFRAME_DESCRIPTION}</td>
							</tr>
						</table>

						<br>
						<table border=0 cellspacing=0 cellpadding=10 width=100%>
							<tr>
								<td>

									<table width="100%">
										<tr>
											<td nowrap align="right">
												<input type="submit" class="crmbutton save"
													value="{$APP.LBL_SAVE_LABEL}"
													onclick="return VTE.Settings.QlikIframe.validateEditForm(this.form);" />
												<input type="button" class="crmbutton cancel"
													value="{$APP.LBL_CANCEL_BUTTON_LABEL}"
													onclick="window.location.href='index.php?module=Settings&amp;action=QlikIframe&amp;parenttab=Settings'" />
											</td>
										</tr>
									</table>

									<table width="70%" border="0" cellspacing="2" cellpadding="5">
										<tr>
											<td class="big"><br><strong>{$MOD.LBL_QLIKIFRAME_CONF}
													{$MOD.LBL_INFORMATION}</strong><br></td>
										</tr>
										<tr>
											<td width="25%" nowrap class="cellLabel">
												<strong>{$MOD.LBL_QLIKIFRAME_CONF_NAME}
												</strong>
												<font color="red">*</font>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo">
													<input type="hidden" name="hidden_confname"
														value="{$CONFINFO.confname}">
													<input type="text" name="confname" class="detailedViewTextBox"
														value="{$CONFINFO.confname}" size=50 </div>
											</td>
										</tr>
										<tr class="mailscanner_config mailscanner_config_account">
											<td nowrap class="cellLabel">
												<strong>QRSurl</strong>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo">
													<input type="hidden" name="hidden_QRSurl"
														value="{$CONFINFO.QRSurl}">
													<input type="text" name="QRSurl" class="detailedViewTextBox"
														value="{$CONFINFO.QRSurl}" size=50>
												</div>
											</td>
										</tr>
										<tr class="mailscanner_config mailscanner_config_account">
											<td nowrap class="cellLabel">
												<strong>{{$MOD.LBL_QLIKIFRAME_CONF_ENDPOIT}}</strong>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo">
													<input type="hidden" name="hidden_endpoint"
														value="{$CONFINFO.endpoint}">
													<input type="text" name="endpoint" class="detailedViewTextBox"
														value="{$CONFINFO.endpoint}" size=50>
												</div>
											</td>
										</tr>
										<tr class="mailscanner_config mailscanner_config_account">
											<td nowrap class="cellLabel">
												<strong>QRSCertfile</strong>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo">
													<input type="hidden" name="hidden_QRSCertfile"
														value="{$CONFINFO.QRSCertfile}">
													<input type="file" name="QRSCertfile" class="detailedViewTextBox"
														value="{$CONFINFO.QRSCertfile}" size=50>{$CONFINFO.QRSCertfile}
												</div>
											</td>
										</tr>

										<tr class="mailscanner_config mailscanner_config_account">
											<td nowrap class="cellLabel">
												<strong>QRSCertkeyfile</strong>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo}">
													<input type="hidden" name="hidden_QRSCertkeyfile"
														value="{$CONFINFO.QRSCertkeyfile}">
													<input type="file" name="QRSCertkeyfile" class="detailedViewTextBox"
														value="{$CONFINFO.QRSCertkeyfile}"
														size=50>{$CONFINFO.QRSCertkeyfile}<br </div>
											</td>
										</tr>

										<tr class="mailscanner_config mailscanner_config_account">
											<td nowrap class="cellLabel">
												<strong>QRSCertkeyfilePassword</strong>

											</td>
											<td width="75%" class="cellText">
												<div class="dvtCellInfo}">
													<input type="hidden" name="hidden_QRSCertkeyfilePassword"
														value="{$CONFINFO.QRSCertkeyfilePassword}">
													<input type="text" name="QRSCertkeyfilePassword"
														class="detailedViewTextBox"
														value="{$CONFINFO.QRSCertkeyfilePassword}" size=50>
												</div>
											</td>
										</tr>





										<tr>
											<td nowrap class="cellLabel"><strong>{$MOD.LBL_STATUS}</strong></td>
											<td class="cellText">
												{assign var="conf_enable" value=""}
												{assign var="conf_disable" value=""}

												{if $CONFINFO.isValid eq '0'}
													{assign var="conf_disable" value="checked='true'"}
												{else}
													{assign var="conf_enable" value="checked='true'"}
												{/if}

												<input type="radio" name="active" class="small" value="true"
													{$conf_enable}> {$MOD.LBL_ENABLE}
												<input type="radio" name="active" class="small" value="false"
													{$conf_disable}> {$MOD.LBL_DISABLE}
											</td>
										</tr>
										<tr>
											<td nowrap class="cellLabel"><strong>DEBUG</strong></td>
											<td class="cellText">
												{assign var="debug_enable" value=""}
												{assign var="debug_disable" value=""}

												{if $CONFINFO.debug eq '0'}
													{assign var="debug_disable" value="checked='true'"}
												{else}
													{assign var="debug_enable" value="checked='true'"}
												{/if}

												<input type="radio" name="debug" class="small" value="true"
													{$debug_enable}> {$MOD.LBL_ENABLE}
												<input type="radio" name="debug" class="small" value="false"
													{$debug_disable}> {$MOD.LBL_DISABLE}
											</td>
										</tr>

									</table>

								</td>
							</tr>
						</table>

					</div>
				</form>

			</td>
		</tr>
	</tbody>
</table>

{* crmv@243983 - moved js code *}