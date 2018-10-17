$(window).load(function() {
  $('#slider').nivoSlider({
  effect:'sliceUp,sliceUpDown,fold,fade,slideInRight',		
  slices:17,
  animSpeed:300,		
  pauseTime:5000,		
  startSlide:0,		
  directionNav:false,		
  directionNavHide:true,		
  controlNav:true,		
  controlNavThumbs:false,		
  controlNavThumbsFromRel:false,		
  keyboardNav:true,		
  pauseOnHover:true,		
  manualAdvance:false,		
  captionOpacity:0.8,		
  beforeChange: function(){},		
  afterChange: function(){},		
  slideshowEnd: function(){}

  });
});

/*sliceDown
sliceDownLeft
sliceUp
sliceUpLeft
sliceUpDown
sliceUpDownLeft
fold
fade
random
slideInRight
slideInLeft
boxRandom
boxRain
boxRainReverse
boxRainGrow
boxRainGrowReverse*/