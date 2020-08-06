
//flash
function flash_contents(file,width,height){
document.writeln("<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH='"+width+"' HEIGHT='"+height+"' id='contents' ALIGN=''>");
document.writeln("<PARAM NAME=movie value='"+file+"' />");
document.writeln("<PARAM NAME=quality VALUE=high>");
document.writeln("<PARAM NAME=bgcolor VALUE=#FFFFFF>");
document.writeln("<PARAM NAME=wmode VALUE=transparent> ");
document.writeln("<embed src='"+file+"' quality='high' bgcolor='#FFFFFF' width='"+width+"' height='"+height+"' name='contents' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />");
document.writeln("</OBJECT>");
}



//TV_AD_MEDIA___
function tv_adplay(file,w,h,mediaName) {
document.write('<object id="'+mediaName+'" name=id="'+mediaName+'" width='+w+' height='+h+' classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,05,0809" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject" VIEWASTEXT>')
document.write('<param name="transparentAtStart" value="True">')
document.write('<param name="transparentAtStop" value="False">')
document.write('<param name="AnimationAtStart" value="0">')
document.write('<param name="Loop" value="true">')
document.write('<param name="AutoStart" value="0">')
document.write('<param name="AutoRewind" value="true">')
document.write('<param name="SendMouseClickEvents" value="True">')
document.write('<param name="DisplaySize" value="0">')
document.write('<param name="AutoSize" value="False">')
document.write('<param name="ShowDisplay" value="False">')
document.write('<param name="ShowControls" value="False">')
document.write('<param name="ShowTracker" value="True">')
document.write('<param name="FileName" value="'+file+'">')
document.write('<param name="Enabled" value="1">')
document.write('<param name="EnableContextMenu" value="1">')
document.write('<param name="EnablePositionControls" value="1">')
document.write('<param name="EnableFullScreenControls" value="1">')
document.write('<param name="ShowPositionControls" value="1">')
document.write('<param name="Mute" value="0">')
document.write('<param name="Rate" value="1">')
document.write('<param name="SAMILang" value="">')
document.write('<param name="SAMIStyle" value="">')
document.write('<param name="SAMIFileName" value="">')
document.write('<param name="ClickToPlay" value="0">')
document.write('<param name="CursorType" value="1">')
document.write('<embed src="'+file+'"   id="'+mediaName+'" Loop=true  quality=high menu="false" bgcolor=#FFFFFF  WIDTH="'+w+'" HEIGHT="'+h+'" autoplay=true controller=true loop=false type="application/x-oleobject"></embed></object>')
} 
//--------------------------------------------------------------