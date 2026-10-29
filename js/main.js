$(document).ready(function () {
	$(".ts-sidebar-menu li a").each(function () {
		if ($(this).next("ul").length > 0) {
			$(this).addClass("parent");
		}
	});

	$(".ts-sidebar-menu li a.parent").each(function () {
		if ($(this).siblings(".more").length === 0) {
			$('<button type="button" class="more" aria-label="Expand menu"><i class="bi bi-chevron-down" aria-hidden="true"></i></button>').insertBefore($(this));
		}
	});

	$(".more").on("click", function () {
		$(this).parent("li").toggleClass("open");
	});

	$(".parent").on("click", function (e) {
		e.preventDefault();
		$(this).parent("li").toggleClass("open");
	});

	if ($.fn.DataTable) {
		$("table#zctb").each(function () {
			var hasHeader = $(this).find("thead th").length > 0;
			var isReady = $.fn.dataTable && $.fn.dataTable.isDataTable && $.fn.dataTable.isDataTable(this);
			if (hasHeader && !isReady) {
				$(this).DataTable({
					pageLength: 10,
					responsive: true
				});
			}
		});
	}

	if ($.fn.fileinput) {
		$("#input-43").fileinput({
			showPreview: false,
			allowedFileExtensions: ["zip", "rar", "gz", "tgz"],
			elErrorContainer: "#errorBlock43"
		});
	}
});
