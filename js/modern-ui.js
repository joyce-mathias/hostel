(function () {
  "use strict";

  if (window.HMSModernUILoaded) return;
  window.HMSModernUILoaded = true;

  var storageKey = "hms-theme";
  var root = document.documentElement;
  var savedTheme = localStorage.getItem(storageKey);

  if (savedTheme === "dark" || savedTheme === "light") {
    root.setAttribute("data-bs-theme", savedTheme);
  }

  function ready(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
    } else {
      callback();
    }
  }

  function iconForField(labelText, field) {
    var text = (labelText || "").toLowerCase();
    var type = (field.getAttribute("type") || field.tagName || "").toLowerCase();

    if (type === "password" || text.indexOf("password") > -1) return "bi-shield-lock";
    if (type === "email" || text.indexOf("email") > -1) return "bi-envelope";
    if (type === "date" || text.indexOf("date") > -1 || text.indexOf("stay") > -1) return "bi-calendar3";
    if (type === "file" || text.indexOf("file") > -1) return "bi-paperclip";
    if (text.indexOf("room") > -1) return "bi-door-open";
    if (text.indexOf("course") > -1) return "bi-journal-bookmark";
    if (text.indexOf("gender") > -1) return "bi-person";
    if (text.indexOf("contact") > -1 || text.indexOf("phone") > -1) return "bi-telephone";
    if (text.indexOf("address") > -1 || text.indexOf("city") > -1 || text.indexOf("state") > -1 || text.indexOf("pin") > -1) return "bi-geo-alt";
    if (text.indexOf("fee") > -1 || text.indexOf("amount") > -1) return "bi-currency-rupee";
    if (text.indexOf("complaint") > -1 || text.indexOf("remark") > -1) return "bi-chat-left-text";
    if (field.tagName.toLowerCase() === "select") return "bi-list-check";
    return "bi-input-cursor-text";
  }

  function updateThemeButtons(theme) {
    document.querySelectorAll("[data-theme-toggle]").forEach(function (button) {
      var isDark = theme === "dark";
      button.setAttribute("aria-label", isDark ? "Switch to light theme" : "Switch to dark theme");
      button.setAttribute("title", isDark ? "Light theme" : "Dark theme");
      button.innerHTML = '<i class="bi ' + (isDark ? "bi-sun" : "bi-moon-stars") + '" aria-hidden="true"></i>';
    });
  }

  function currentTheme() {
    return root.getAttribute("data-bs-theme") === "dark" ? "dark" : "light";
  }

  function enhanceSidebar() {
    var sidebar = document.querySelector(".ts-sidebar");
    var menuButton = document.querySelector(".menu-btn");
    if (!sidebar || !menuButton) return;

    if (!document.querySelector(".hms-sidebar-backdrop")) {
      var backdrop = document.createElement("div");
      backdrop.className = "hms-sidebar-backdrop";
      document.body.appendChild(backdrop);
      backdrop.addEventListener("click", closeSidebar);
    }

    function openSidebar() {
      sidebar.classList.add("menu-open");
      document.body.classList.add("sidebar-open");
      menuButton.setAttribute("aria-expanded", "true");
    }

    function closeSidebar() {
      sidebar.classList.remove("menu-open");
      document.body.classList.remove("sidebar-open");
      menuButton.setAttribute("aria-expanded", "false");
    }

    menuButton.addEventListener("click", function () {
      if (document.body.classList.contains("sidebar-open")) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") closeSidebar();
    });

    sidebar.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        if (window.matchMedia("(max-width: 991.98px)").matches) closeSidebar();
      });
    });
  }

  function markActiveNavigation() {
    var current = window.location.pathname.split("/").pop() || "index.php";
    document.querySelectorAll(".ts-sidebar-menu a[href]").forEach(function (link) {
      var href = link.getAttribute("href");
      if (!href || href === "#") return;
      var normalized = href.split("?")[0].replace(/\/$/, "");
      if (normalized === current || normalized.endsWith("/" + current)) {
        var item = link.closest("li");
        if (item) {
          item.classList.add("active");
          var parent = item.parentElement && item.parentElement.closest("li");
          if (parent) parent.classList.add("open");
        }
      }
    });
  }

  function enhanceForms() {
    document.querySelectorAll("form").forEach(function (form) {
      form.classList.add("hms-form");
      form.querySelectorAll(".form-group").forEach(function (group) {
        var label = group.querySelector(":scope > label.control-label, :scope > label");
        var field = group.querySelector("input:not([type='hidden']):not([type='checkbox']):not([type='radio']), select, textarea");
        var fieldHolder = field && field.parentElement;

        if (label && label.querySelector("h1, h2, h3, h4")) {
          label.classList.add("hms-section-label");
          return;
        }

        if (label && field && field.required) {
          label.classList.add("is-required");
        }

        if (!field || !fieldHolder || fieldHolder.classList.contains("hms-field")) return;

        fieldHolder.classList.add("hms-field");
        if (field.tagName.toLowerCase() !== "textarea" && field.getAttribute("type") !== "file") {
          fieldHolder.classList.add("has-icon");
          var icon = document.createElement("i");
          icon.className = "bi " + iconForField(label ? label.textContent : "", field) + " hms-field-icon";
          icon.setAttribute("aria-hidden", "true");
          fieldHolder.insertBefore(icon, field);
        }
      });
    });
  }

  function enhanceTables() {
    document.querySelectorAll("table").forEach(function (table) {
      table.classList.add("table", "align-middle");
      if (!table.parentElement.classList.contains("table-responsive") && table.closest(".panel-body")) {
        var wrapper = document.createElement("div");
        wrapper.className = "table-responsive";
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
      }
    });

    if (window.jQuery && jQuery.fn && jQuery.fn.DataTable) {
      jQuery("table#zctb, table.hms-datatable").each(function () {
        var table = this;
        var hasHeader = jQuery(table).find("thead th").length > 0;
        var alreadyReady = jQuery.fn.dataTable && jQuery.fn.dataTable.isDataTable && jQuery.fn.dataTable.isDataTable(table);
        if (hasHeader && !alreadyReady) {
          jQuery(table).DataTable({
            pageLength: 10,
            responsive: true,
            language: {
              search: "",
              searchPlaceholder: "Search records",
              lengthMenu: "Show _MENU_",
              info: "Showing _START_ to _END_ of _TOTAL_"
            }
          });
        }
      });
    }
  }

  function downloadTableCsv(table) {
    var rows = Array.prototype.slice.call(table.querySelectorAll("tr"));
    var csv = rows.map(function (row) {
      return Array.prototype.slice.call(row.querySelectorAll("th, td")).map(function (cell) {
        var text = cell.textContent.trim().replace(/\s+/g, " ");
        return '"' + text.replace(/"/g, '""') + '"';
      }).join(",");
    }).join("\n");

    var blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    var link = document.createElement("a");
    var pageName = (document.title || "hostel-report").toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
    link.href = URL.createObjectURL(blob);
    link.download = pageName + ".csv";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(link.href);
  }

  function enhanceExportButtons() {
    document.querySelectorAll("table#zctb").forEach(function (table) {
      if (!table.querySelector("thead th") || table.dataset.exportReady === "true") return;
      table.dataset.exportReady = "true";

      var panel = table.closest(".panel, .hms-card");
      var heading = panel && panel.querySelector(".panel-heading, .hms-card-header");
      var button = document.createElement("button");
      button.type = "button";
      button.className = "btn btn-default btn-sm hms-export-button";
      button.innerHTML = '<i class="bi bi-download" aria-hidden="true"></i> Export CSV';
      button.addEventListener("click", function () {
        downloadTableCsv(table);
      });

      if (heading) {
        heading.appendChild(button);
      } else {
        table.parentNode.insertBefore(button, table);
      }
    });
  }

  function enhanceProgressMeters() {
    document.querySelectorAll("[data-progress]").forEach(function (meter) {
      var value = parseFloat(meter.getAttribute("data-progress"));
      if (Number.isNaN(value)) value = 0;
      meter.style.width = Math.max(0, Math.min(100, value)) + "%";
    });
  }

  function enhanceStatusBadges() {
    var statusMap = {
      "new": "badge-status-new",
      "pending": "badge-status-new",
      "in process": "badge-status-process",
      "closed": "badge-status-closed",
      "active": "badge-status-closed",
      "inactive": "badge-status-danger",
      "without food": "badge-status-danger",
      "with food": "badge-status-closed"
    };

    document.querySelectorAll("td, .hms-status").forEach(function (cell) {
      if (cell.children.length > 0) return;
      var value = cell.textContent.trim().replace(/\s+/g, " ");
      var key = value.toLowerCase();
      if (!statusMap[key]) return;
      cell.innerHTML = '<span class="badge ' + statusMap[key] + '">' + value + "</span>";
    });
  }

  function enhancePasswordToggles() {
    document.querySelectorAll("[data-password-toggle]").forEach(function (button) {
      button.addEventListener("click", function () {
        var input = document.querySelector(button.getAttribute("data-password-toggle"));
        if (!input) return;
        var showing = input.getAttribute("type") === "text";
        input.setAttribute("type", showing ? "password" : "text");
        button.innerHTML = '<i class="bi ' + (showing ? "bi-eye" : "bi-eye-slash") + '" aria-hidden="true"></i>';
        button.setAttribute("aria-label", showing ? "Show password" : "Hide password");
      });
    });
  }

  function enhanceTooltips() {
    if (!window.bootstrap || !bootstrap.Tooltip) return;
    document.querySelectorAll("[title]").forEach(function (el) {
      if (!el.getAttribute("data-bs-toggle")) {
        el.setAttribute("data-bs-toggle", "tooltip");
      }
      bootstrap.Tooltip.getOrCreateInstance(el);
    });
  }

  ready(function () {
    updateThemeButtons(currentTheme());

    document.querySelectorAll("[data-theme-toggle]").forEach(function (button) {
      button.addEventListener("click", function () {
        var nextTheme = currentTheme() === "dark" ? "light" : "dark";
        root.setAttribute("data-bs-theme", nextTheme);
        localStorage.setItem(storageKey, nextTheme);
        updateThemeButtons(nextTheme);
      });
    });

    enhanceSidebar();
    markActiveNavigation();
    enhanceForms();
    enhanceTables();
    enhanceExportButtons();
    enhanceProgressMeters();
    enhanceStatusBadges();
    enhancePasswordToggles();
    enhanceTooltips();
  });
})();
