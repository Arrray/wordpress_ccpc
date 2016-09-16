/**
 * Created by Scott on 16/6/3.
 */
window.onload = function(){
    if(!document.getElementsByClassName){
        document.getElementsByClassName = function(className, element){
            var children = (element || document).getElementsByTagName('*');
            var elements = [];
            for (var i=0; i<children.length; i++){
                var child = children[i];
                var classNames = child.className.split(' ');
                for (var j=0; j<classNames.length; j++){
                    if (classNames[j] == className){
                        elements.push(child);
                        break;
                    }
                }
            }
            return elements;
        };
    }
    var aTitle = document.getElementsByClassName('subMainOptionTitle');
    var aContent = document.getElementsByClassName('subMainOptionContent');
    // (function () {
    //     for(var j = 1; j < aTitle.length; j++){
    //         aTitle[j].className = 'subMainOptionTitle';
    //     }
    //     for(var k = 0; k < aContent.length; k++){
    //         aContent[k].className = 'subMainOptionContent none';
    //     }
    //     aTitle[1].className = 'subMainOptionTitle subMainOptionTitleActive';
    //     aContent[0].className = 'subMainOptionContent';
    // })();
    for (var i = 1; i < aTitle.length; i++){
        aTitle[i].index = i;
        aTitle[i].onmouseover = function(){
            for(var j = 1; j < aTitle.length; j++){
                aTitle[j].className = 'subMainOptionTitle';
            }
            for(var k = 0; k < aContent.length; k++){
                aContent[k].className = 'subMainOptionContent none';
            }
            this.className = 'subMainOptionTitle subMainOptionTitleActive';
            aContent[this.index-1].className = 'subMainOptionContent';
        };
    }
    
    var aNavLevel1 = document.getElementsByClassName('menu')[0].children;
    console.log(aNavLevel1[1].children);
    for (var m = 0; m < aNavLevel1.length; m++){
	//console.log(aNavLevel1[m].children);
	aNavLevel1[m].onmouseover = function(){
	    if(this.children.length == 2){
		this.children[1].className = 'sub-menu navHover';
	    }
	};
	aNavLevel1[m].onmouseout = function(){
	    if(this.children.length == 2){
		this.children[1].className = 'sub-menu';
	    }
	};
    }
    myFocus.set({
        id:'noticeSlides',
        pattern:'mF_expo2010'
    });
};
