
function scrollTable(tableID, visibleCount, interval)
{
    var table = document.getElementById(tableID);
    visibleCount = visibleCount;    
    if (table.rows.length<=visibleCount)
        return;
    var tmp = table.rows[0].innerHTML;
    for (i=1; i<table.rows.length;i++)
        table.rows[i-1].innerHTML = table.rows[i].innerHTML;
    table.rows[table.rows.length-1].innerHTML = tmp;
    setTimeout("scrollTable('"+tableID+"',"+visibleCount.toString()+","+interval.toString()+")",interval);
}
function scrollUp(id,totalStep, currentStep,tagChild,delay, scrollSpeed)
{
    var obj = document.getElementById(id);
    if (currentStep == 0 ) // clone the first object and append 
    {
        c = obj.getElementsByTagName(tagChild)[0];        
        obj.appendChild(c.cloneNode(true));
    }
    if (currentStep >=totalStep)
    {        
        c = obj.getElementsByTagName(tagChild)[0];        
        obj.removeChild(c);
        obj.style.top = '0px';
        fn = "scrollUp('"+id+"',"+totalStep.toString()+",0,'"+tagChild+"'," + delay.toString()+ "," +scrollSpeed.toString()+")";
        setTimeout(fn,delay);
        return;
    }
    var top = parseInt(obj.style.top);
    if (!top)
        top = 0;
    top = top - 2;
    obj.style.top = top +'px';
    currentStep++;
    fn = "scrollUp('"+id+"',"+totalStep.toString()+","+currentStep.toString()+",'"+tagChild+"'," + delay.toString() +"," + scrollSpeed.toString()+")";
    setTimeout(fn,scrollSpeed);
}

function scrollDown(id,totalStep, currentStep,tagChild,delay, scrollSpeed)
{
    var obj = document.getElementById(id);
    els = obj.getElementsByTagName(tagChild);        
    if (currentStep == 0 ) // clone the first object and append 
    {        
        c = els[els.length-1];        
        obj.insertBefore(c.cloneNode(true),obj.firstChild);
    }
    if (currentStep >=totalStep)
    {        
        c = els[els.length-1];        
        obj.removeChild(c);
        obj.style.bottom = '0px';
        fn = "scrollDown('"+id+"',"+totalStep.toString()+",0,'"+tagChild+"'," + delay.toString()+ "," +scrollSpeed.toString()+")";
        setTimeout(fn,delay);
        return;
    }
    var top = parseInt(obj.style.bottom);
    if (!top)
        top = 0;
    top = top - 2;
    obj.style.bottom = top +'px';
    currentStep++;
    fn = "scrollDown('"+id+"',"+totalStep.toString()+","+currentStep.toString()+",'"+tagChild+"'," + delay.toString() +"," + scrollSpeed.toString()+")";
    setTimeout(fn,scrollSpeed);
}


function moveUp(id,tagChild,delay)
{
    var obj = document.getElementById(id);
    
    c = obj.getElementsByTagName(tagChild)[0];        
    obj.appendChild(c.cloneNode(true));
    c = obj.getElementsByTagName(tagChild)[0];        
    obj.removeChild(c);

    fn = "moveUp('"+id +"','"+tagChild+"'," + delay.toString() +")";
    setTimeout(fn,delay);
}


function scrollLeft(id,totalStep, currentStep,tagChild,delay, scrollSpeed)
{
    var obj = document.getElementById(id);
    if (currentStep == 0 ) // clone the first object and append 
    {
        c = obj.getElementsByTagName(tagChild)[0];        
        obj.appendChild(c.cloneNode(true));
    }
    if (currentStep >=totalStep)
    {        
        c = obj.getElementsByTagName(tagChild)[0];        
        obj.removeChild(c);
        obj.style.left = '0px';
        fn = "scrollLeft('"+id+"',"+totalStep.toString()+",0,'"+tagChild+"'," + delay.toString()+ "," +scrollSpeed.toString()+")";
        setTimeout(fn,delay);
        return;
    }
    var top = parseInt(obj.style.left);
    if (!top)
        left = 0;
    left = left - 2;
    obj.style.left = left +'px';
    currentStep++;
    fn = "scrollLeft('"+id+"',"+totalStep.toString()+","+currentStep.toString()+",'"+tagChild+"'," + delay.toString() +"," + scrollSpeed.toString()+")";
    setTimeout(fn,scrollSpeed);
}