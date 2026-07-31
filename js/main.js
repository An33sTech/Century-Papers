document.addEventListener("DOMContentLoaded", function () {
  const historySection = document.querySelector("#history");

  if (historySection) {
    let isAnimating = false;
    let targetScroll = 0;
    let currentTimeline = null;

    function animateScroll() {
      if (!currentTimeline) return;
      const current = currentTimeline.scrollLeft;
      const distance = targetScroll - current;
      const step = distance * 0.2; // Smoothness factor

      if (Math.abs(distance) > 0.5) {
        currentTimeline.scrollLeft += step;
        requestAnimationFrame(animateScroll);
      } else {
        currentTimeline.scrollLeft = targetScroll;
        isAnimating = false;
      }
    }

    // Wheel scrolling listener - active on both timeline containers when hovered
    const timelines = historySection.querySelectorAll(
      ".ca-horizontal-timeline",
    );
    timelines.forEach((timeline) => {
      timeline.addEventListener(
        "wheel",
        (evt) => {
          const maxScroll = timeline.scrollWidth - timeline.clientWidth;
          const currentScroll = timeline.scrollLeft;

          if (currentTimeline !== timeline) {
            currentTimeline = timeline;
            targetScroll = currentScroll;
            isAnimating = false;
          }

          if (!isAnimating) targetScroll = timeline.scrollLeft;

          const goingUp = evt.deltaY < 0;
          const goingDown = evt.deltaY > 0;

          const canScrollLeft = goingUp && currentScroll > 5;
          const canScrollRight = goingDown && currentScroll < maxScroll - 5;

          if (canScrollLeft || canScrollRight) {
            evt.preventDefault();
            targetScroll += evt.deltaY * 2;
            targetScroll = Math.max(0, Math.min(targetScroll, maxScroll));

            if (!isAnimating) {
              isAnimating = true;
              requestAnimationFrame(animateScroll);
            }
          }
        },
        { passive: false },
      );
    });
  }

  // Corporate Governance Scroll Spy, Smooth Scroll, and Sticky Toggle
  const govTabs = document.querySelectorAll(".gov-tablink");
  const govSections = document.querySelectorAll(".gov-section");
  const tabsContainer = document.querySelector(".gov_tabs_container");
  const banner = document.querySelector(".innovative_banner");

  if (govTabs.length > 0 && govSections.length > 0 && tabsContainer) {
    const header = document.querySelector(".industify_fn_header");
    const headerHeight = header ? header.offsetHeight : 90;

    function handleStickyAndSpy() {
      const scrollPosition = window.pageYOffset;
      // Use the bottom of the banner as the threshold
      const bannerBottom = banner
        ? banner.offsetTop + banner.offsetHeight
        : 300;
      const initialOffset = bannerBottom - headerHeight;

      // Sticky Toggle
      if (scrollPosition >= initialOffset) {
        tabsContainer.classList.add("fixed-nav");
        tabsContainer.style.top = 0 + "px";
      } else {
        tabsContainer.classList.remove("fixed-nav");
        tabsContainer.style.top = "";
      }

      // Scroll Spy Highlight
      let currentSectionId = "";
      const tabsHeight = tabsContainer.offsetHeight || 60;
      const spyPosition = scrollPosition + headerHeight + tabsHeight + 20;

      govSections.forEach((section) => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        if (
          spyPosition >= sectionTop &&
          spyPosition < sectionTop + sectionHeight
        ) {
          currentSectionId = "#" + section.getAttribute("id");
        }
      });

      if (!currentSectionId && govSections.length > 0) {
        currentSectionId = "#" + govSections[0].getAttribute("id");
      }

      govTabs.forEach((tab) => {
        if (tab.getAttribute("href") === currentSectionId) {
          tab.classList.add("active");
        } else {
          tab.classList.remove("active");
        }
      });
    }

    // Smooth scroll on tab click
    govTabs.forEach((tab) => {
      tab.addEventListener("click", function (e) {
        e.preventDefault();
        const targetId = this.getAttribute("href");
        const targetSection = document.querySelector(targetId);
        if (targetSection) {
          const tabsHeight = tabsContainer.offsetHeight || 60;
          const targetPosition =
            targetSection.getBoundingClientRect().top +
            window.pageYOffset -
            headerHeight -
            tabsHeight +
            10;

          window.scrollTo({
            top: targetPosition,
            behavior: "smooth",
          });
        }
      });
    });

    window.addEventListener("scroll", handleStickyAndSpy);
    window.addEventListener("resize", handleStickyAndSpy);
    handleStickyAndSpy(); // Initial run
  }

  // Accordion Sidebar Sub-Menu toggle
  const accordionToggles = document.querySelectorAll(".accordion-toggle");
  accordionToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const parentLi = this.parentElement;
      const subMenu = parentLi.querySelector(".side-sub-menu");
      if (subMenu) {
        const isOpen = parentLi.classList.contains("open");
        if (isOpen) {
          parentLi.classList.remove("open");
          subMenu.style.display = "none";
        } else {
          parentLi.classList.add("open");
          subMenu.style.display = "block";
        }
      }
    });
  });
});
