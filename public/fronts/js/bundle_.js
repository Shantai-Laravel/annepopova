//   slides
$(function() {
  $(document).ready(function(){
    let collectionSlider = $('.collectionSlider');
    let categorySlider = $('.categorySlider');
    let oneCollectionSlider = $('.oneCollectionSlider');
    let navCollection = $('.navCollection');

    $('.additional').slick({
      dots: false,
      infinite: true,
      speed: 800,
      slidesToShow: 3,
      slidesToScroll: 1,
      swipe: true
    })
    $('.sliderBanner').slick({
      dots: false,
      infinite: true,
      speed: 800,
      slidesToShow: 1,
      slidesToScroll: 1,
      swipe: true,
      nextArrow: $('.nextBanner'),
      prevArrow: $('.prevBanner')
    })
    for (let i = 0; i < collectionSlider.length; i++) {
      $(collectionSlider[i]).slick({
        dots: false,
        infinite: true,
        speed: 800,
        slidesToShow: 1,
        slidesToScroll: 1,
        swipe: true
      })
    }

    for (let i = 0; i < categorySlider.length; i++) {
      $(categorySlider[i]).slick({
        dots: false,
        infinite: true,
        speed: 800,
        slidesToShow: 3,
        slidesToScroll: 1,
        swipe: true
      })
    }

    for (let i = 0; i < oneCollectionSlider.length; i++) {
      $(oneCollectionSlider[i]).slick({
        infinite: true,
        speed: 800,
        slidesToShow: 1,
        slidesToScroll: 1,
        swipe: true,
        arrows: false,
        centerMode: false,
        focusOnSelect: true,
        infinite: true,
        vertical: true,
        asNavFor: navCollection[i]
      })

      $(navCollection[i]).slick({
        infinite: true,
        speed: 800,
        slidesToShow: 1,
        slidesToScroll: 1,
        swipe: false,
        arrows: false,
        centerMode: false,
        focusOnSelect: true,
        infinite: false,
        vertical: true,
        asNavFor: oneCollectionSlider[i],
        draggable: false
      })
    }

    $(window).scroll(function() {
      if($(window).scrollTop() > 20) {
        $("header").addClass("onScroll")
      } else {
        $("header").removeClass("onScroll")
      }
    })
  });
});

//select size
$(function() {
  $(document).ready(function() {

    const selectSize = (e) => {
      const parent = e.target.closest('div');
      const mass = parent.querySelectorAll('span');

      document.getElementById('sizeError').classList.remove('show');

      for (let i of mass) {
        i.classList.remove('selected')
      }

      e.target.classList.add('selected');
    }

    function toggleCat(){
      if($(this).hasClass('active')) {
        $(this).removeClass('active')
        $(this).next().removeClass('show')
      } else {
        $(this).addClass('active')
        $(this).next().addClass('show')
      }
    }

    function closeFilter() {
      document.getElementById('filterInner').classList.remove('show')
      document.getElementById('filterButton').classList.remove('active')
    }

    function toggleFilter(e) {
      if($(e.target).hasClass('active')) {
        $(e.target).next().removeClass('show')
        $(e.target).removeClass('active')
      } else {
        $(e.target).next().addClass('show')
        $(e.target).addClass('active')
      }
    }

    function show() {
      $('.description').find('.title').removeClass('minus')
      $('.description').find('.title').next().removeClass('show')

      if($(this).next().hasClass('show')) {
        $(this).removeClass('minus')
        $(this).next().removeClass('show')
      } else {
        $(this).addClass('minus')
        $(this).next().addClass('show')
      }
    }

    $('.oneProduct').on('click', '.addToWish', function() {
      $(this).toggleClass('addedToWish')
    })

    $('.paymentMethod').click(function() {
      $('.paymentMethod').removeClass('selected')
      $(this).addClass('selected')
    })

    if(!$('main').hasClass('.categoryContent')) {
      $('#filterButton').click((e) => {
        toggleFilter(e);
      })
      $('.filterCat').on('click', '.filterButton', toggleCat);
      $('.filterContainer').on('click', '#closeFilter', closeFilter);
    }

    $('.sizeContainer').on('click', "span", selectSize);
    $('.description').on('click', '.title', show);
  });
});

// home about

$(function() {
  $(document).ready(function() {
      const ab1 = document.getElementById('about1')
      const ab2 = document.getElementById('about2')
      const ab3 = document.getElementById('about3')
      const ab4 = document.getElementById('about4')
      const title = document.getElementById('titleAbout')
      let scroll2 = window.pageYOffset;

      if($('main').hasClass('.homeContent')) {
        window.addEventListener('scroll', function (e) {
          let offset = window.pageYOffset;
          let top1 = 70;
          let top2 = 100;
          let top3 = 105;
          let top4 = -40;
          scroll2 = offset

          ab1.style.top = top1 - scroll2 / 80 + 'px'
          ab2.style.top = top2 - scroll2 / 20 + 'px'
          ab3.style.top = top3 - scroll2 / 100 + 'px'
          ab4.style.top = top4 - 100 + scroll2 / 30 + 'px'
          title.style.top = top4 + scroll2 / 70 + 'px'
      })
      }
  });
});

// contact page

$(function() {
  $(document).ready(function() {
    const contact1 = document.getElementById('contact1')
    const contact2 = document.getElementById('contact2')
    const contact3 = document.getElementById('contact3')
    const contact4 = document.getElementById('contact4')
    let scroll2 = window.pageYOffset;

      if ($('main').hasClass('contactContent')){
        window.addEventListener('scroll', function (e) {
          let offset = window.pageYOffset;
          scroll2 = offset

          contact3.style.transform = `scale(${1 + scroll2 * 0.00009} )`
          contact4.style.transform = `scale(${1 + scroll2 * 0.00009} )`

          contact1.style.transform = `translateY(${1 + scroll2 * 0.17}px)`
          contact2.style.transform = `translateY(${1.1 + scroll2 * 0.14}px)`
      })
      }
  });
});


// category page
$(function() {
  $(document).ready(function() {
    let buttonFilter = document.getElementById('filterButton');
    const loungewear = document.getElementById("loungewearButton");
    const jewerly = document.getElementById("jewerlyButton");
    const header = $("header")

    loungewear.addEventListener("click", function() {
      header.removeClass("jewerly")
      header.addClass("loungewear")
    })
    jewerly.addEventListener("click", function() {
      header.addClass("jewerly")
      header.removeClass("loungewear")
    })

    function toggleFilter(e) {
      if($(this).hasClass('active')) {
        $(this).next().removeClass('show')
        $(this).removeClass('active')
      } else {
        $(this).next().addClass('show')
        $(this).addClass('active')
      }
    }

    if($('main').hasClass('.categoryContent')) {
      buttonFilter.addEventListener('click', toggleFilter)
    }
  });
});


// about page

$(function() {
  $(document).ready(function() {
    const scaleIn = document.getElementById('scaleIn')
    const scaleOut = document.getElementById('scaleOut')
    const scaleOut2 = document.getElementById('scaleOut2')
    const top = document.getElementById('top')
    const scaleOut3 = document.getElementById('scaleOut3')
    const top2= document.getElementById('top2')
    const top3= document.getElementById('top3')
    const bottom= document.getElementById('bottom')
    const bottom2= document.getElementById('bottom2')
    let scroll2 = window.pageYOffset;

    if($('main').hasClass('aboutContent')) {
      window.addEventListener('scroll', function (e) {
        let offset = window.pageYOffset;
        scroll2 = offset
        if(1.5 - scroll2 * 0.0005 > 1 && 1.5 - scroll2 * 0.00005 < 2) {
          scaleIn.style.transform = `scale(${1.5 - scroll2 * 0.00005} )`
        }
        scaleOut.style.transform = `scale(${1 + scroll2 * 0.00005} )`
        scaleOut2.style.transform = `scale(${1 + scroll2 * 0.00005} )`

        top.style.top = -170 + 1 + scroll2 * 0.05 + 'px'
    })

    window.addEventListener('wheel', function (e) {
      let offset = window.pageYOffset;
      scroll2 = offset
      if(-1 + scroll2 * 0.00005 > 1 && -1 + scroll2 * 0.00005 < 2) {
        scaleOut3.style.transform = `scale(${-1 + scroll2 * 0.00005} )`
      }

      top2.style.bottom = offset * 1.1 - scroll2  + 60 + 'px'
      top3.style.top = offset * 1.1 - scroll2  + 0.7 + 'px'
      bottom.style.top = offset * 1.1 - scroll2  + 0.7 + 'px'
      bottom2.style.top = offset * 1.1 - scroll2  + 0.7 + 'px'
  })
    }
  });
});
