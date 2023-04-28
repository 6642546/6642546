function $dom(){
//获取对象
var elements = new Array(); 
   for (var i = 0; i < arguments.length; i++) 
   { 
     var element = arguments[i]; 
     if (typeof element == 'string') 
       element = document.getElementById(element); 
     if (arguments.length == 1) 
       return element; 
     elements.push(element); 
   } 
   return elements; 
}
var months = new Array("Jan", "Feb", "Mar", "Api", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); 
var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
var days = new Array("Sun","Mon", "Tue", "Wed", "Thu", "Fri", "Sat"); 
var today;

document.writeln("<div id='Calendar' style='position:absolute; z-index:1; visibility: hidden; filter:\"progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#999999,strength=3)\"'></div>");

function getDays(month, year)
{ 
//下面的这段代码是判断当前是否是闰年的 
if (1 == month) 
   return ((0 == year % 4) && (0 != (year % 100))) || (0 == year % 400) ? 29 : 28; 
else 
   return daysInMonth[month]; 
}

function getToday() 
{ 
//得到今天的年,月,日 
this.now = new Date(); 
this.year = this.now.getFullYear(); 
this.month = this.now.getMonth(); 
this.day = this.now.getDate(); 
}

function getStringDay(str) 
{ 
//得到输入框的年,月,日
var str=str.split("/")

this.now = new Date(parseFloat(str[0]),parseFloat(str[1])-1,parseFloat(str[2])); 
this.year = this.now.getFullYear(); 
this.month = this.now.getMonth(); 
this.day = this.now.getDate(); 
}

function newCalendar() { 
var parseYear = parseInt($dom("Year").options[$dom("Year").selectedIndex].value); 
var newCal = new Date(parseYear, $dom("Month").selectedIndex, 1); 
var day = -1; 
var startDay = newCal.getDay(); 
var daily = 0; 

if ((today.year == newCal.getFullYear()) &&(today.month == newCal.getMonth())) 
   day = today.day; 
  
var tableCal = $dom("_calendar"); 
var intDaysInMonth =getDays(newCal.getMonth(), newCal.getFullYear());

for (var intWeek = 1; intWeek < tableCal.rows.length;intWeek++) 
   for (var intDay = 0;intDay < tableCal.rows[intWeek].cells.length;intDay++) 
   { 
    var cell = tableCal.rows[intWeek].cells[intDay]; 
    if ((intDay == startDay) && (0 == daily)) 
     daily = 1; 
    
    if(day==daily) //今天，调用今天的Class 
    {
     cell.style.background='#6699CC';
     cell.style.color='#FFFFFF';
     //cell.style.fontWeight='bold';
    }
    else if(intDay==6) //周六 
     cell.style.color='green'; 
    else if (intDay==0) //周日 
     cell.style.color='red';
   
    if ((daily > 0) && (daily <= intDaysInMonth)) 
    { 
     cell.innerHTML = daily; 
     daily++; 
    } 
    else 
     cell.innerText = ""; 
   } 
}

function GetDate(InputBox,event)
{ 
var sDate;
var obj;
obj = event.srcElement ? event.srcElement : event.target;
//这段代码处理鼠标点击的情况 
if (obj.tagName == "TD") 
   if (getText(obj) != "") 
   { 
    sDate = $dom("Year").value + "/" + $dom("Month").value + "/" + getText(obj);
    $dom(InputBox).value=sDate;
    HiddenCalendar();
   } 
}

function getText(o){
if(navigator.appName.indexOf("Explorer") > -1){
return o.innerText;
} else{
return o.textContent;
}
}
function HiddenCalendar()
{
//关闭选择窗口
$dom("Calendar").style.visibility='hidden';
}

function ShowCalendar(InputBox)
{
//显示日历，及定位
var x,y,intLoop,intWeeks,intDays;
var DivContent;
var year,month,day;
var o=InputBox;
var oid=o.id;
var thisyear; //真正的今年年份

if(!oid)oid=o.name;

thisyear=new getToday();
thisyear=thisyear.year;

today = o.value;
if(isDate(today))
   today = new getStringDay(today);
else
   today = new getToday(); 

//显示的位置
x=o.offsetLeft;
y=o.offsetTop;
while(o=o.offsetParent)
{
   x+=o.offsetLeft;
   y+=o.offsetTop;
}
$dom("Calendar").style.left=x+2+"px";
$dom("Calendar").style.top=y+20+"px";
$dom("Calendar").style.visibility="visible";

//下面开始输出日历表格(border-color:#9DBAF7)
DivContent="<table border='0' cellspacing='0' style='border:1px solid #06f; background-color:#EDF2FC'>";
DivContent+="<tr>";
DivContent+="<td style='border-bottom:1px solid #06f; background-color:#C7D8FA'>";

//年
DivContent+="<select name='Year' id='Year' onChange='newCalendar()' style='font-family:Verdana; font-size:12px'>";
for (intLoop = thisyear-1; intLoop < (thisyear + 5); intLoop++) 
   DivContent+="<option value= " + intLoop + " " + (today.year == intLoop ? "Selected" : "") + ">" + intLoop + "</option>"; 
DivContent+="</select>";

//月
DivContent+="<select name='Month' id='Month' onChange='newCalendar()' style='font-family:Verdana; font-size:12px'>";
for (intLoop = 0; intLoop < months.length; intLoop++) 
   DivContent+="<option value= " + (intLoop + 1) + " " + (today.month == intLoop ? "Selected" : "") + ">" + months[intLoop] + "</option>"; 
DivContent+="</select>";

DivContent+="</td>";

DivContent+="<td style='border-bottom:1px solid #06f; background-color:#C7D8FA; font-weight:bold; font-size:16px; padding-top:2px; color:#4477FF; cursor:pointer' align='center' title='关闭' onClick='javascript:HiddenCalendar()'>x</td>";
DivContent+="</tr>";

DivContent+="<tr><td align='center' colspan='2'>";
DivContent+="<table id='_calendar' border='0' width='100%' cellpadding='0' cellspacing='0'>";

//星期
DivContent+="<tr>";
for (intLoop = 0; intLoop < days.length; intLoop++) 
   DivContent+="<td align='center' style='font-size:12px;border-bottom:1px solid #bbb;border-right:1px solid #bbb;' height=14>" + days[intLoop] + "</td>"; 
DivContent+="</tr>";

//天
for (intWeeks = 0; intWeeks < 6; intWeeks++)
{ 
   DivContent+="<tr>"; 
   for (intDays = 0; intDays < days.length; intDays++) 
    DivContent+="<td onClick='GetDate(\"" + oid + "\",event)' style='cursor:pointer; border-right:1px solid #bbb; border-bottom:1px solid #bbb; color:#215DC6; font-family:Verdana; font-size:12px' align='center'></td>"; 
   DivContent+="</tr>"; 
} 
DivContent+="</table></td></tr></table>";

$dom("Calendar").innerHTML=DivContent;
newCalendar();
}

function isDate(dateStr)
{ 
var datePat = /^(\d{4})(\-)(\d{1,2})(\-)(\d{1,2})$/;
var matchArray = dateStr.match(datePat);
if (matchArray == null) return false; 
var month = matchArray[3];
var day = matchArray[5]; 
var year = matchArray[1]; 
if (month < 1 || month > 12) return false; 
if (day < 1 || day > 31) return false; 
if ((month==4 || month==6 || month==9 || month==11) && day==31) return false; 
if (month == 2)
{
   var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)); 
   if (day > 29 || (day==29 && !isleap)) return false; 
} 
return true;
}
