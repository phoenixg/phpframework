var waitting = 1;
var secondLeft = waitting;
var timer;
var sourceObj;
var number;
function getObject(objectId)//获取id的函数
	{
		if(document.getElementById && document.getElementById(objectId)) {
		// W3C DOM
		return document.getElementById(objectId);
		} else if (document.all && document.all(objectId)) {
		// MSIE 4 DOM
		return document.all(objectId);
		} else if (document.layers && document.layers[objectId]) {
		// NN 4 DOM.. note: this won't find nested layers
		return document.layers[objectId];
		} else {
		return false;
		}
	}
function SetTimer()//主导航时间延迟的函数
	{
		for(j=1; j <10; j++){
			if (j == number){
				if(getObject("mm"+j)!=false){
					getObject("mm"+ number).className = "menuhover";
					getObject("mb"+ number).className = "";
				}
			}
			else{
				 if(getObject("mm"+j)!=false){
					getObject("mm"+ j).className = "";
					getObject("mb"+ j).className = "hide";
				}
			}
		}
	}
function CheckTime()//设置时间延迟后
	{
		secondLeft--;
		if ( secondLeft == 0 )
		{
		clearInterval(timer);
		SetTimer();
		}
	}
function showM(thisobj,Num)//主导航鼠标滑过函数,带时间延迟
	{
		number = Num;
		sourceObj = thisobj;
		secondLeft = 1;
		timer = setTimeout('CheckTime()',100);
	}
function OnMouseLeft()//主导航鼠标移出函数,清除时间函数
	{
		clearInterval(timer);
	}
function mmenuURL()//主导航、二级导航显示函数
{
 var cUrl=location.href; //取得当前文件名
  var str_url=myjson;
  for (var i=0,j=str_url.length;i<j;i++){
    if(cUrl.indexOf(str_url[i])>0){ //判断获取的文件名中是否包含str_url中的元素
      window.load=showM(this,(i+1));
    }else{
      getObject("mm1").className="menuhover";
    }
  }
}

window.load=mmenuURL()
