let viewportBottom,viewportTop,viewportWidth;
const parallaxContainer = $('.fce-parallax');
const riskWarningContainer = $('.cookieInformationContainer');
const parallaxFactor = 0.2;
const parallaxZoom = 1.6;
const noEffectSpacer = 200;
const mobileDeviceMaxWidth = 1200;

function parallax(){
    if(parallaxContainer.length > 0){
        parallaxContainer.each(function(index, item){
            let background = $(item).find('.background img');
            let height = $(item).attr('data-height');
            if(background.length > 0){
                background.removeAttr('height').removeAttr('width');
                $(item).find('.background').css('height',(height*parallaxZoom)).append(background);
                $(item).find('.background > div').detach();
                $(item).css('height',height + 'px');
            }
        });
    }

    $(window).on('scroll',function(){
        parallaxContainer.each(function(index, item){
            getCurrentViewport();
            if(isVisible($(item))){
                startParallax($(item));
            }
        });
    });
}

function isVisible(el){
    let elPos = el.offset();
    if((viewportTop + noEffectSpacer) <= elPos.top && (viewportBottom - noEffectSpacer) > elPos.top){
        return true;
    }
}

function getCurrentViewport(){
    viewportTop = $(window).scrollTop();
    viewportWidth = $(window).innerWidth();
    viewportBottom = $(window).scrollTop() + $(window).innerHeight() - riskWarningContainer.innerHeight();
}

function startParallax(el){
    if(viewportWidth > mobileDeviceMaxWidth || el.attr('data-disableonmobiledevices') === '0'){
        let elHeight = el.innerHeight();
        let bgHeight = el.find('.background').innerHeight();
        let effectRange = bgHeight - elHeight;
        let elPos = el.offset();
        let diff = viewportBottom - noEffectSpacer - elPos.top;
        let top = diff * parallaxFactor;
        if(top < effectRange){
            el.find('.background').css('top',-Math.abs(top));
        } else {
            el.find('.background').css('top',-Math.abs(effectRange));
        }
    }
}

function bsSliderFix(){
    $('a.carousel-control-prev').on('click',function(e){
        e.preventDefault();
        $(this).closest('.fce-slider').carousel('prev');
    })
    $('a.carousel-control-next').on('click',function(e){
        e.preventDefault();
        $(this).closest('.fce-slider').carousel('next');
    })
}