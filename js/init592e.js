window.addEventListener("load", function () {
    "use strict";
    jQuery("body").addClass("preloader_loaded");
}),
    (function (e) {
        "use strict";
        var t = {
            init: function () {
                t.BgImg(),
                    t.imgToSVG(),
                    t.alphaSlider(),
                    t.parallax(),
                    t.serviceQueryCarousel(),
                    t.totop(),
                    t.languageOpener(),
                    t.mobileMenuOpener(),
                    t.mobileSubmenuOpener(),
                    t.triangleCalc(),
                    t.languageCalc(),
                    t.supportBlockBgCalc(),
                    t.lightGallery(),
                    t.isotope(),
                    t.kenburnsy(),
                    t.popupYoutube(),
                    t.spacer(),
                    t.counter(),
                    t.fiveStars(),
                    t.reviewSlider(),
                    t.reCalcProjectElegant(),
                    t.estimateWidgetCalc(),
                    t.blogShapeCalc(),
                    // t.contactForm(),
                    t.justified(),
                    t.portfolioFilter();
            },
            getIncludedHTML: function () {
                var i,
                    n = e("[data-html]"),
                    a = n.length,
                    o = 0;
                n.each(function () {
                    var n = e(this),
                        s = n.data("html");
                    s &&
                        (((i = new XMLHttpRequest()).onreadystatechange = function () {
                            if (4 === this.readyState && 200 === this.status) {
                                n.before(this.responseText);
                                var i = n.attr("data-index");
                                if (void 0 !== i && !1 !== i) {
                                    var s = n.data("index"),
                                        r = n.data("count"),
                                        l = n.siblings(".industify_fn_random_services");
                                    if (l.length) {
                                        var d = l.find("li");
                                        d.addClass("disable");
                                        var c = Array.from({ length: d.length }, (e, t) => t + 1),
                                            p = c.indexOf(s);
                                        p > -1 && c.splice(p, 1);
                                        var f = c
                                            .sort(function () {
                                                return 0.5 - Math.random();
                                            })
                                            .slice(0, r);
                                        e.each(f, function (e, t) {
                                            d.eq(t - 1).removeClass("disable");
                                        }),
                                            d.filter(".disable").remove();
                                    }
                                }
                                n.remove(), ++o === a && t.init();
                            }
                        }),
                        i.open("GET.html", s),
                        i.send());
                }),
                    a || t.init();
            },
            portfolioFilter: function () {
                var i = e(".portfolio_list .filter"),
                    n = e(".portfolio_list .filter > a"),
                    a = e(".portfolio_list .fn_filter"),
                    o = e(".portfolio_list .fn_filter a"),
                    s = e(".portfolio_list .filter span.spinner"),
                    r = e(".industify_fn_portfolio_page .portfolio_list_in"),
                    l = e(".industify_fn_portfolio_list").isotope({});
                e(window).on("click", function () {
                    a.removeClass("opened"), i.removeClass("opened");
                }),
                    n.on("click", function (e) {
                        return (
                            e.stopPropagation(),
                            a.hasClass("opened")
                                ? (a.removeClass("opened"), i.removeClass("opened"))
                                : (a.addClass("opened"), i.addClass("opened")),
                            !1
                        );
                    }),
                    o.on("click", function () {
                        var d = e(this),
                            c = d.data("filter"),
                            p = d.text();
                        return (
                            r.addClass("active"),
                            s.addClass("active"),
                            o.removeClass("active"),
                            d.addClass("active"),
                            n.html(p),
                            a.removeClass("opened"),
                            i.removeClass("opened"),
                            t.doAjaxCall(c, l, d, s),
                            !1
                        );
                    });
            },
            doAjaxCall: function (e, t, i, n) {
                setTimeout(function () {
                    t.isotope({ filter: e }), i.removeClass("active"), n.removeClass("active");
                }, 500);
            },
            justified: function () {
                e(".fn_cs_justified").each(function () {
                    var t = e(this),
                        i = t.attr("data-height"),
                        n = t.attr("data-gutter");
                    void 0 !== i && void 0 !== n ? ("" !== i && (i = i), "" !== n && (n = n)) : ((i = 300), (n = 10)),
                        t.parent().css({ padding: n + "px" }),
                        e().justifiedGallery &&
                            t.justifiedGallery({
                                rowHeight: i,
                                lastRow: "nojustify",
                                margins: n,
                                refreshTime: 500,
                                refreshSensitivity: 0,
                                maxRowHeight: null,
                                border: 0,
                                captions: !1,
                                randomize: !1,
                            });
                });
            },
            blogShapeCalc: function () {
                var t = e(".industify_fn_postlist .post");
                t.length &&
                    t.each(function () {
                        var t = e(this),
                            i = Math.floor(t.width() - 100),
                            n = t.find(".shape2");
                        i > 0 && n.length && n.css({ borderLeftWidth: i + "px" });
                    });
            },
            estimateWidgetCalc: function () {
                e(".industify_fn_widget_estimate").each(function () {
                    var t = e(this),
                        i = t.find(".helper1"),
                        n = t.find(".helper2"),
                        a = t.find(".helper3"),
                        o = t.find(".helper4"),
                        s = t.find(".helper5"),
                        r = t.find(".helper6"),
                        l = t.outerWidth(),
                        d = Math.floor((80 * l) / 300),
                        c = l - d,
                        p = Math.floor((55 * d) / 80);
                    i.css({ borderLeftWidth: d + "px", borderTopWidth: p + "px" }),
                        n.css({ borderRightWidth: c + "px", borderTopWidth: p + "px" }),
                        a.css({ borderLeftWidth: d + "px", borderTopWidth: d + "px" }),
                        o.css({ borderRightWidth: c + "px", borderTopWidth: d + "px" }),
                        s.css({ borderLeftWidth: d + "px", borderTopWidth: d + "px" }),
                        r.css({ borderRightWidth: c + "px", borderTopWidth: d + "px" });
                });
            },
            reCalcProjectElegant: function () {
                e(".fn_cs_project_elegant_list .right_part").each(function () {
                    var t = e(this),
                        i = t.height() - 2 * (t.width() * (300 / 420));
                    t.find(".item")
                        .eq(0)
                        .find(".title_holder")
                        .css({ height: i + "px", bottom: "-" + i + "px" });
                });
            },
            fiveStars: function () {
                e(".fn_cs_review__stars").each(function () {
                    var t = e(this),
                        i = 20 * t.data("stars") + 1,
                        n = 100 - i;
                    t.find(".rating_absolute").css({ width: i + "px" }),
                        t.find(".rating_relative").css({ width: n + "px" }),
                        t.find(".rating_relative div").css({ transform: "translateX(-" + i + "px)" });
                });
            },
            reviewSlider: function () {
                e(".fn_cs_personal_reviews .r_list").each(function () {
                    var i = e(this),
                        n = i.find(".swiper-container"),
                        a = new Swiper(n, {
                            loop: !0,
                            slidesPerView: 3,
                            spaceBetween: 70,
                            speed: 1e3,
                            loopAdditionalSlides: 10,
                            autoplay: { delay: 7e4, disableOnInteraction: !1 },
                            on: {
                                init: function () {
                                    i.closest(".fn_cs_personal_reviews").addClass("ready");
                                },
                                autoplayStop: function () {
                                    a.autoplay.start();
                                },
                                slideChange: function () {
                                    t.imgToSVG();
                                },
                            },
                            pagination: {
                                el: ".fn_cs_swiper__progress",
                                type: "custom",
                                renderCustom: function (e, t, i) {
                                    var a,
                                        o,
                                        s = t - 1,
                                        r = n.find('.swiper-slide[data-swiper-slide-index="' + s + '"]');
                                    n.find(".r_item").removeClass("fn_vision"),
                                        r.find(".r_item").addClass("fn_vision"),
                                        r.next().find(".r_item").addClass("fn_vision"),
                                        r.next().next().find(".r_item").addClass("fn_vision");
                                    var l = n.find(".fn_cs_swiper__progress");
                                    l.find(".all span").length &&
                                        (l.hasClass("fill")
                                            ? ((o = "0px"), (a = parseInt((t / i) * 100) / 100))
                                            : ((a = parseInt((1 / i) * 100) / 100),
                                              (o = ((t - 1) * parseInt((100 / i) * 100)) / 100 + "px")),
                                        l
                                            .find(".all span")
                                            .css({
                                                transform: "translate3d(" + o + ",0px,0px) scaleX(" + a + ") scaleY(1)",
                                            }),
                                        t < 10 && (t = "0" + t),
                                        i < 10 && (i = "0" + i),
                                        l.find(".current").html(t),
                                        l.find(".total").html(i));
                                },
                            },
                            navigation: { nextEl: ".mynext-button", prevEl: ".myprev-button" },
                            breakpoints: {
                                0: { slidesPerView: 1, spaceBetween: 20 },
                                768: { slidesPerView: 1, spaceBetween: 20 },
                                1200: { slidesPerView: 2, spaceBetween: 20 },
                                1600: { slidesPerView: 3, spaceBetween: 70 },
                            },
                        });
                }),
                    t.imgToSVG();
            },
            counter: function () {
                e(".fn_cs_counter").each(function () {
                    var t = e(this);
                    t.waypoint({
                        handler: function () {
                            t.hasClass("stop") ||
                                t.addClass("stop").countTo({
                                    refreshInterval: 50,
                                    formatter: function (e, t) {
                                        return e.toFixed(t.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
                                    },
                                });
                        },
                        offset: "90%",
                    });
                });
            },
            spacer: function () {
                e("[data-margin-bottom]").each(function () {
                    var t = e(this);
                    t.css({ marginBottom: t.data("margin-bottom") + "px" });
                }),
                    e("[data-margin-top]").each(function () {
                        var t = e(this);
                        t.css({ marginTop: t.data("margin-top") + "px" });
                    }),
                    e("[data-padding-bottom]").each(function () {
                        var t = e(this);
                        t.css({ paddingBottom: t.data("padding-bottom") + "px" });
                    }),
                    e("[data-padding-top]").each(function () {
                        var t = e(this);
                        t.css({ paddingTop: t.data("padding-top") + "px" });
                    });
            },
            popupYoutube: function () {
                e(".popup-youtube").each(function () {
                    e(this).magnificPopup({
                        disableOn: 700,
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 160,
                        preloader: !1,
                        fixedContentPos: !1,
                    });
                });
            },
            kenburnsy: function () {
                e(".fn_cs_kenburnsy").each(function () {
                    var t = e(this),
                        i = t.data("interval");
                    t.kenburnsy({ fullscreen: !0, duration: i });
                });
            },
            isotope: function () {
                var t = e(".masonry");
                e().isotope &&
                    t.each(function () {
                        e(this).isotope({ itemSelector: ".masonry_in", masonry: {} });
                    });
            },
            lightGallery: function () {
                e().lightGallery &&
                    e(".fn_cs_lightgallery").each(function () {
                        var t = e(this);
                        t.lightGallery(),
                            t.length && t.data("lightGallery").destroy(!0),
                            e(this).lightGallery({
                                selector: ".lightbox",
                                thumbnail: 1,
                                loadYoutubeThumbnail: !1,
                                loadVimeoThumbnail: !1,
                                showThumbByDefault: !1,
                                mode: "lg-fade",
                                download: !1,
                                getCaptionFromTitleOrAlt: !1,
                            });
                    });
            },
            supportBlockBgCalc: function () {
                e(".fn_cs_support_block .support_block").each(function () {
                    var t = e(this),
                        i = t.outerHeight() - 30;
                    t.find(".img_wrap span").css({ borderTopWidth: i + "px" });
                });
            },
            triangleCalc: function () {
                var t = e(".industify_fn_header .toll_free");
                if (t.length) {
                    var i = t.find("span.shape2"),
                        n = t.find("span.shape3"),
                        a = t.outerHeight();
                    i.css({ borderTopWidth: a - 18 + "px" }), n.css({ borderTopWidth: a + "px" });
                }
                var o = e(".industify_fn_mobilemenu_wrap .m_toll_free");
                if (o.length) {
                    var s = o.find("span.shape2"),
                        r = o.find("span.shape3"),
                        l = o.outerHeight();
                    s.css({ borderTopWidth: l - 18 + "px" }), r.css({ borderTopWidth: l + "px" });
                }
            },
            mobileSubmenuOpener: function () {
                e(".vert_menu_list a").on("click", function () {
                    var t = e(this),
                        i = t.parent("li"),
                        n = t.parents("li"),
                        a = i.parents("ul.sub-menu"),
                        o = t.next(),
                        s = t.closest(".vert_menu_list").find("li");
                    if ((s.removeClass("opened"), o.length))
                        return (
                            i.hasClass("active")
                                ? (i.removeClass("active"), o.slideUp())
                                : (n.hasClass("opened") || n.addClass("opened"),
                                  s.each(function () {
                                      var t = e(this);
                                      t.hasClass("opened") || t.find("ul.sub-menu").slideUp();
                                  }),
                                  s.removeClass("active"),
                                  a.parent("li").addClass("active"),
                                  i.addClass("active"),
                                  o.slideDown()),
                            !1
                        );
                });
            },
            mobileMenuOpener: function () {
                e(".industify_fn_mobilemenu_wrap .hamburger").on("click", function () {
                    var t = e(this),
                        i = e(".industify_fn_mobilemenu_wrap .mobilemenu");
                    return (
                        t.hasClass("is-active")
                            ? (t.removeClass("is-active"), i.removeClass("opened"), i.slideUp(500))
                            : (t.addClass("is-active"), i.addClass("opened"), i.slideDown(500)),
                        !1
                    );
                });
            },
            languageCalc: function () {
                var t = e(".industify_fn_moving_lang");
                if (t.length) {
                    var i = e(".lang_switcher"),
                        n = i.offset().top,
                        a = i.offset().left;
                    t.css({ position: "absolute", left: a + "px", top: n + 30 + "px" });
                }
            },
            languageOpener: function () {
                var t = e(".lang_switcher");
                if (t.length) {
                    var i = t.find("span.click"),
                        n = t.find("ul"),
                        a = t.offset().top,
                        o = t.offset().left;
                    e(".industify_fn_wrapper_all").append(
                        '<div class="industify_fn_moving_lang"><ul>' + n.html() + "</ul></div>"
                    );
                    var s = e(".industify_fn_moving_lang"),
                        r = s.find("ul");
                    s.css({ position: "absolute", left: o + "px", top: a + 30 + "px" }),
                        t.length &&
                            (i.on("click", function () {
                                t.hasClass("opened")
                                    ? (s.removeClass("opened"), t.removeClass("opened"))
                                    : (t.addClass("opened"), s.addClass("opened"));
                            }),
                            e(window).on("click", function () {
                                t.removeClass("opened"), s.removeClass("opened");
                            }),
                            r.on("click", function (e) {
                                e.stopPropagation();
                            }),
                            t.on("click", function (e) {
                                e.stopPropagation();
                            }));
                }
            },
            totop: function () {
                var t = e(".industify_fn_totop");
                t.length &&
                    t.on("click", function (i) {
                        i.preventDefault();
                        var n = t.offset().top / 5;
                        return (
                            (n = (n = n > 1500 ? 1500 : n) < 500 ? 500 : n),
                            e("html, body").animate({ scrollTop: 0 }, n),
                            !1
                        );
                    });
            },
            serviceQueryCarousel: function () {
                e(".fn_cs_service_query .owl-carousel").each(function () {
                    var i,
                        n,
                        a,
                        o = e(this),
                        s = o.closest(".fn_cs_service_query"),
                        r = parseInt(s.data("column-count"));
                    switch ((e.isNumeric(r) || (r = 4), r)) {
                        case 5:
                            (i = 5), (n = 4), (a = 2);
                            break;
                        case 4:
                            (i = 4), (n = 3), (a = 2);
                            break;
                        case 3:
                            (i = 3), (n = 3), (a = 2);
                            break;
                        case 2:
                            (i = 2), (n = 2), (a = 2);
                            break;
                        case 1:
                            (i = 1), (n = 1), (a = 1);
                    }
                    o.owlCarousel({
                        loop: !0,
                        margin: 0,
                        nav: !1,
                        items: 4,
                        dots: !1,
                        smartSpeed: 1e3,
                        responsive: { 0: { items: 1 }, 480: { items: a }, 1200: { items: n }, 1400: { items: i } },
                    });
                    var l = s.find(".owl_control .fn_prev"),
                        d = s.find(".owl_control .fn_next");
                    l.on("click", function () {
                        return o.trigger("prev.owl"), !1;
                    }),
                        d.on("click", function () {
                            return o.trigger("next.owl"), !1;
                        }),
                        t.imgToSVG();
                }),
                    t.imgToSVG(),
                    t.BgImg();
            },
            parallax: function () {
                e("#scene").parallax();
            },
            alphaSlider: function () {
                t.BgImg(),
                    e(".industify_slider_alpha").each(function () {
                        var t,
                            i = e(this),
                            n = i.data("autoplay-switch"),
                            a = i.data("effect");
                        t = "enabled" === n ? i.data("autoplay-time") : 8e4;
                        new Swiper(i, {
                            centeredSlides: !1,
                            slideToClickedSlide: !1,
                            slidesPerView: 1,
                            spaceBetween: 0,
                            preloadImages: !1,
                            lazyLoading: !1,
                            autoplay: { delay: t, disableOnInteraction: !1 },
                            initialSlide: 0,
                            navigation: { nextEl: i.find(".fn_next"), prevEl: i.find(".fn_prev") },
                            effect: a,
                            coverflowEffect: { rotate: 30, slideShadows: !1 },
                            flipEffect: { rotate: 30, slideShadows: !1 },
                            cubeEffect: { slideShadows: !1 },
                            loop: !0,
                            pagination: { el: i.find(".swiper-pagination"), type: "progressbar" },
                            speed: 1e3,
                        });
                    });
            },
            imgToSVG: function () {
                e("img.fn__svg").each(function () {
                    var t = e(this),
                        i = t.attr("class"),
                        n = t.attr("src");
                    e.get(
                        n,
                        function (n) {
                            var a = e(n).find("svg");
                            void 0 !== i && (a = a.attr("class", i + " replaced-svg")), t.replaceWith(a);
                        },
                        "xml"
                    );
                });
            },
            BgImg: function () {
                e("*[data-bg-img]").each(function () {
                    var t = e(this),
                        i = t.attr("data-bg-img");
                    void 0 !== i && t.css({ backgroundImage: "url(" + i + ")" });
                }),
                    e("*[data-fn-bg-img]").each(function () {
                        var t = e(this),
                            i = t.attr("data-fn-bg-img");
                        void 0 !== i && t.css({ backgroundImage: "url(" + i + ")" });
                    });
            },
            // contactForm: function () {
            //     e("#send_message").on("click", function () {
            //         var t = e(".contact_form"),
            //             i = e("#name").val(),
            //             n = e("#email").val(),
            //             a = e("#message").val(),
            //             o = t.find(".success"),
            //             s = o.data("success"),
            //             r = t.data("email");
            //         return (
            //             o.empty(),
            //             "" === i || "" === n || "" === a || "" === r
            //                 ? e(".empty_notice").slideDown(500).delay(2e3).slideUp(500)
            //                 : e.post(
            //                       "modal/contact.html",
            //                       { ajax_name: i, ajax_email: n, ajax_emailto: r, ajax_message: a },
            //                       function (e) {
            //                           o.append(e),
            //                               o.find(".contact_error").length
            //                                   ? o.slideDown(500).delay(2e3).slideUp(500)
            //                                   : (o.append("<span class='contact_success'>" + s + "</span>"),
            //                                     o.slideDown(500).delay(4e3).slideUp(500)),
            //                               "" === e && t[0].reset();
            //                       }
            //                   ),
            //             !1
            //         );
            //     });
            // },
        };
        e(document).ready(function () {
            t.getIncludedHTML();
        }),
            e(window).on("resize", function () {
                t.isotope(),
                    t.triangleCalc(),
                    t.languageCalc(),
                    t.supportBlockBgCalc(),
                    t.reCalcProjectElegant(),
                    t.estimateWidgetCalc(),
                    t.blogShapeCalc();
            }),
            e(window).on("load", function () {
                e("body").addClass("preloader_loaded"),
                    t.isotope(),
                    setTimeout(function () {
                        e(".industify_fn_portfolio_list").length && e(".industify_fn_portfolio_list").isotope({}),
                            t.isotope();
                    }, 600);
            });
    })(jQuery);
