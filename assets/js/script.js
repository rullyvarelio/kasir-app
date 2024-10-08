// SIDEBAR COLLAPSE
const toggleSidebar = document.querySelector("nav .toggle-sidebar");

if (sidebar.classList.contains("hide")) {
  allDropdown.forEach((item) => {
    const a = item.parentElement.querySelector("a:first-child");
    a.classList.remove("active");
    item.classList.remove("show");
  });
}

toggleSidebar.addEventListener("click", function () {
  sidebar.classList.toggle("hide");

  if (sidebar.classList.contains("hide")) {
    allDropdown.forEach((item) => {
      const a = item.parentElement.querySelector("a:first-child");
      a.classList.remove("active");
      item.classList.remove("show");
    });
  }
});

sidebar.addEventListener("mouseleave", function () {
  if (this.classList.contains("hide")) {
    allDropdown.forEach((item) => {
      const a = item.parentElement.querySelector("a:first-child");
      a.classList.remove("active");
      item.classList.remove("show");
    });
  }
});

sidebar.addEventListener("mouseenter", function () {
  if (this.classList.contains("hide")) {
    allDropdown.forEach((item) => {
      const a = item.parentElement.querySelector("a:first-child");
      a.classList.remove("active");
      item.classList.remove("show");
    });
  }
});

const profile = document.querySelector("nav, .profile");
const dropdownProfile = profile.querySelector(".profile-link");
const imgProfile = profile.querySelector("img");

imgProfile.addEventListener("click", function () {
  dropdownProfile.classList.toggle("show");
});

window.addEventListener("click", function (e) {
  if (e.target !== imgProfile) {
    if (e.target !== dropdownProfile) {
      if (dropdownProfile.classList.contains("show")) {
        dropdownProfile.classList.remove("show");
      }
    }
  }
});

const moreIcons = document.querySelectorAll(".more-ic");
moreIcons.forEach(function (icon) {
  const dropdown = icon.parentElement.querySelector(".more-link");

  icon.addEventListener("click", function (e) {
    e.stopPropagation(); // Prevent event from bubbling to the window
    dropdown.classList.toggle("show");
  });

  // Close the dropdown if clicking outside
  window.addEventListener("click", function (e) {
    if (e.target !== icon && e.target !== dropdown) {
      dropdown.classList.remove("show");
    }
  });
});
