
// // Define fonts before initializing DataTable
// pdfMake.fonts = {
//   en: {
//     normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Regular.ttf',
//     bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Medium.ttf',
//     italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Italic.ttf',
//     bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-MediumItalic.ttf'
//   },
//   ar: {
//     normal: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Regular.ttf',
//     bold: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Bold.ttf',
//     italics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Regular.ttf',
//     bolditalics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Bold.ttf'
//   },
//   hi: {
//     normal: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Regular.ttf',
//     bold: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Bold.ttf',
//     italics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Regular.ttf',
//     bolditalics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Bold.ttf'
//   },
// };


function imageUrlToBase64(url) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = 'Anonymous'; // Important for CORS

    img.onload = function () {
      const canvas = document.createElement('canvas');
      canvas.width = img.naturalWidth;
      canvas.height = img.naturalHeight;

      const ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0);

      try {
        const base64 = canvas.toDataURL('image/png');
        resolve(base64);
      } catch (e) {
        reject(e);
      }
    };

    img.onerror = function () {
      reject(new Error('Failed to load image'));
    };

    img.src = url;
  });
}


let headerImageBase64 = '';

// First load the image
(async function () {
  try {
    headerImageBase64 = await imageUrlToBase64(imageUrl);

  } catch (err) {
    console.error("Image loading failed", err);
  }
})();



// displayDataTable Readme

// _selector              // ✅ Required: CSS selector of the target <table> element (e.g., '.my-table' or '#userTable').

// columnDefs = [         // 🔧 Column-specific settings. Use to disable sorting, set alignment, etc.
//   {
//     targets: [-1],       // Target the last column
//     orderable: false,    // Disable sorting on this column
//     className: 'dt-body-right dt-head-right' // Apply custom alignment classes
//   }
// ]

// pageLength = 50        // 🔢 Number of records shown per page by default

// aaSorting = []         // 🔃 Default sorting setup
//                        // Format: [ [colIndex, 'asc' or 'desc'] ]
//                        // Example: [ [0, 'asc'], [2, 'desc'] ]
//                        // Empty array disables initial sorting

// searching = true       // 🔍 Enable or disable the search box

// ordering = true        // ↕️ Enable or disable column sorting

// paging = true          // 📄 Enable or disable pagination

// info = true            // ℹ️ Show table information (e.g., “Showing 1 to 10 of 50 entries”)

// rm_export_btn = []     // 🖨️ Export buttons to remove (if using DataTables buttons extension)
//                        // Options: "btn-copy", "btn-excel", "btn-csv", "btn-pdf", "btn-print"
//                        // Use "btn-all" to remove all export buttons
//                        // Example: ["btn-pdf", "btn-print"]



let displayDataTable = (_selector,
  columnDefs = [
    {
      targets: [-1],
      orderable: false,
      className: 'dt-body-right dt-head-right'
    }
  ],
  pageLength = 50, aaSorting = [], searching = true, ordering = true, paging = true, info = true, orientation = 'portrait', rm_export_btn = []

) => {
  const exportOptions = {
    columns: 'thead th:not(.noExport)'
  };

  let displayDataTable_obj = $('.' + _selector)
    .on('preInit.dt', function (e, settings) {
      let api = new $.fn.dataTable.Api(settings);
      $.each(rm_export_btn, function (key, expt_select) {
        if (expt_select === "btn-all") {
          api.buttons().remove();
        } else {
          api.buttons('.' + expt_select).remove();
        }
      });
    }).DataTable({
      aaSorting: aaSorting,
      columnDefs: columnDefs,
      ordering: ordering,
      paging: paging,
      info: info,
      autoWidth: false,
      searching: searching,
      rowReorder: {
        selector: 'td:nth-child(2)'
      },
      responsive: true,

      language: {
        lengthMenu: '_MENU_',
        processing: `
          <div class="custom-processing">
            <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
            <span class="sr-only">Loading...</span>
          </div>`,
        emptyTable: `
          <div align="center" class="dataTables_empty">
            No data available in table <br><br>
            <img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br>
            <span class="text-success bolds">
              <i class="fa fa-arrow-left"></i> Add new record or search with different criteria.
            </span>
          </div>`
      },
      dom:
        '<"dt-layout-row d-flex align-items-center justify-content-between mb-2"' +
        '<"dt-layout-cell dt-layout-start pl-0 "f>' +
        '<"dt-layout-cell dt-layout-end text-end"lB>' +
        '>' +
        'rt' +
        '<"dt-layout-row d-flex align-items-center justify-content-between mt-2"' +
        '<"dt-layout-cell dt-layout-start "i>' +
        '<"dt-layout-cell dt-layout-end text-end"p>' +
        '>',
      pageLength: pageLength,
      lengthMenu: [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]],




      buttons: [
        {
          extend: 'copyHtml5',
          text: '<i class="fa fa-files-o"></i>',
          titleAttr: 'Copy',
          title: $('.' + _selector).data("exportTitle"),
          exportOptions
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: 'Excel',

          title: $('.' + _selector).data("exportTitle"),
          filename: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          exportOptions
        },
        {
          extend: 'csvHtml5',
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: 'CSV',
          filename: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          exportOptions
        },


        {
          extend: 'pdf',
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: 'PDF',
          className: "btn-pdf",
          title: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYY HH_mm_ss'),
          pageSize: 'A4', // Page size (A4)
          orientation: orientation, // <-- this sets landscape orientation
          exportOptions: {
            stripHtml: true,
            columns: ["thead th:not(.noExport)"],
            format: {
              body: function (data, rowIdx, colIdx, node) {
                // Use node.innerText (or node.textContent) to extract only what is visible
                return node.innerText.trim();
              }
            }
          },
          customize: function (doc) {


            // Set page margins and table width
            var now = new Date();
            var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
            doc.content.splice(0, 1); // Remove default title (if any)
            var availableWidth = 595.28 - 20; // A4 width (595.28) - left margin (20) - right margin (20)
            // Add title and HR line
            doc.content.unshift(
              {
                text: $('.' + _selector).data("exportTitle"),
                alignment: 'center',
                fontSize: 14,
                bold: true,
                margin: [0, 7, 0, 5], // [left, top, right, bottom] margins
                color: '#3785e6',  // White
              }
            );

            doc.pageMargins = headerImageBase64 ? [10, 120, 10, 20] : [10, 10, 10, 20]
            // doc.pageMargins = [10, 120, 10,20]; // [left, top, right, bottom] margins
            doc.defaultStyle = {
              font: pdf_language_font,  //this it langauge to change if en ar hi 
              fontSize: 9,
              alignment: pdf_language_align
            };
            doc.styles.tableHeader = {
              alignment: pdf_language_align,
              bold: true,
              fontSize: 11
            };

            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');





            // Apply Devanagari font to Hindi content
            doc.content.forEach(function (item) {
              if (item.table) {
                item.table.body.forEach(function (row) {
                  row.forEach(function (cell) {
                    // Check for English text                      
                    cell.padding = [0, 0, 0, 0]; // Top, Right, Bottom, Left
                    cell.margin = [0, 0, 0, 0];
                    if (/[A-Za-z0-9]/.test(cell.text)) {
                      // If any English letter or number is found
                      cell.font = 'en';
                    } else {
                      // Pure decided language
                      cell.font = pdf_language_font;
                    }

                  });
                });
              }
            });

            // Header with image and text
            doc.header = function () {
              return headerImageBase64 ? {
                image: headerImageBase64,
                width: 575.28,
                height: 100,
                alignment: 'center',
                margin: [0, 20, 0, 0]
              } : {};
            };


            // Add footer with page numbers
            doc.footer = function (currentPage, pageCount) {
              var currentDate = new Date();
              var formattedDate = currentDate.toLocaleString();
              return {
                columns: [
                  {
                    alignment: 'left',
                    fontSize: 9,
                    font: 'en',
                    text: ['Created on: ', { text: formattedDate }]
                  },
                  {
                    alignment: 'right',
                    font: 'en',
                    text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                    fontSize: 9,

                  }
                ],
                margin: [10, 0, 10, 20] // [left, top, right, bottom] margins
              }



            };

          }

        },
        {
          extend: 'print',
          text: '<i class="fa fa-print"></i>',
          titleAttr: 'Print',
          title: $('.' + _selector).data("exportTitle"),
          customize: function (win) {
            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
            $(win.document.body).find('table').addClass('display').css('font-size', '14px');
            $(win.document.body).find('h1').css('text-align', 'center');
          },
          exportOptions: {
            stripHtml: false,
            ...exportOptions,

            //code added to prevent print anchor tag href url on print
            format: {
              body: function (data, row, column, node) {

                if (typeof data === 'string' && data.includes('<a')) {

                  const wrapper = document.createElement('div');
                  wrapper.innerHTML = data;

                  // Remove href from ALL <a> tags inside the cell
                  wrapper.querySelectorAll('a').forEach(a => {
                    a.removeAttribute('href');
                    a.removeAttribute('onclick');
                    a.removeAttribute('data-toggle');
                    a.removeAttribute('data-placement');
                    a.removeAttribute('title');
                  });

                  return wrapper.innerHTML; // return cleaned HTML
                }

                return data;
              }
            }


            //code added to prevent print anchor tag href url on print
          }
        },
        {
          extend: 'colvis',
          text: '<i class="fa fa-columns"></i>',
          titleAttr: 'Columns',
          title: $('.' + _selector).data("exportTitle"),
          className: 'btn btn-primary colvis-left',
          postfixButtons: ['colvisRestore']
        }
      ],
      initComplete: function () {
        $('.dt-length').addClass('ss-dt-length');
        $('.dt-search input').addClass('pl-0');
      }
    });

};


$(document).ready(function () {
  var _dtselector = "example"; // without the dot
  var table = $('.' + _dtselector).DataTable({
    "aaSorting": [],
    "columnDefs": [
      {
        targets: [-1], // last column
        orderable: false,
        className: 'dt-body-right dt-head-right'
      }
    ],
    rowReorder: {
      selector: 'td:nth-child(2)'
    },
    responsive: 'true',
    language: {
      lengthMenu: '_MENU_', // Only show the dropdown, no text
      processing: '<div class="custom-processing"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>',
      emptyTable: `<div align="center" class="dataTables_empty">No data available in table <br> <br><img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>`
    },

    dom:
      '<" dt-layout-row d-flex align-items-center justify-content-between mb-2"' +
      '<"dt-layout-cell dt-layout-start pl-0 "f>' +
      '<"dt-layout-cell dt-layout-end text-end"lB>' +
      '>' +
      'rt' +
      '<" dt-layout-row d-flex align-items-center justify-content-between mt-2"' +
      '<"dt-layout-cell dt-layout-start "i>' +
      '<"dt-layout-cell dt-layout-end  text-end"p>' +
      '>',
    "pageLength": 50,
    "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]],
    buttons: [

      {
        extend: 'copyHtml5',
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: 'Copy',
        title: $('.' + _dtselector).data("exportTitle"),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },

      {
        extend: 'excelHtml5',
        text: '<i class="fa fa-file-excel-o"></i>',
        titleAttr: 'Excel',
        title: $('.' + _dtselector).data("exportTitle"),
        filename: $('.' + _dtselector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },

      {
        extend: 'csvHtml5',
        text: '<i class="fa fa-file-text-o"></i>',
        titleAttr: 'CSV',
        filename: $('.' + _dtselector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },


      {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf-o"></i>',
        titleAttr: 'PDF',
        className: "btn-pdf",
        title: $('.' + _dtselector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
        pageSize: 'A4', // Page size (A4)
        exportOptions: {
          stripHtml: true,
          columns: ["thead th:not(.noExport)"],
          format: {
            body: function (data, rowIdx, colIdx, node) {
              // Use node.innerText (or node.textContent) to extract only what is visible
              return node.innerText.trim();
            }
          }
        },
        customize: function (doc) {


          // Set page margins and table width
          var now = new Date();
          var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
          doc.content.splice(0, 1); // Remove default title (if any)
          var availableWidth = 595.28 - 20; // A4 width (595.28) - left margin (20) - right margin (20)
          // Add title and HR line
          doc.content.unshift(
            {
              text: $('.' + _dtselector).data("exportTitle"),
              alignment: 'center',
              fontSize: 14,
              bold: true,
              margin: [0, 7, 0, 5], // [left, top, right, bottom] margins
              color: '#3785e6',  // White
            }
          );

          doc.pageMargins = headerImageBase64 ? [10, 120, 10, 20] : [10, 10, 10, 20]
          // doc.pageMargins = [10, 120, 10,20]; // [left, top, right, bottom] margins
          doc.defaultStyle = {
            font: pdf_language_font,  //this it langauge to change if en ar hi 
            fontSize: 9,
            alignment: pdf_language_align
          };
          doc.styles.tableHeader = {
            alignment: pdf_language_align,
            bold: true,
            fontSize: 11
          };

          doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');





          // Apply Devanagari font to Hindi content
          doc.content.forEach(function (item) {
            if (item.table) {
              item.table.body.forEach(function (row) {
                row.forEach(function (cell) {
                  // Check for English text                      
                  cell.padding = [0, 0, 0, 0]; // Top, Right, Bottom, Left
                  cell.margin = [0, 0, 0, 0];
                  if (/[A-Za-z0-9]/.test(cell.text)) {
                    // If any English letter or number is found
                    cell.font = 'en';
                  } else {
                    // Pure decided language
                    cell.font = pdf_language_font;
                  }

                });
              });
            }
          });

          // Header with image and text
          doc.header = function () {
            return headerImageBase64 ? {
              image: headerImageBase64,
              width: 575.28,
              height: 100,
              alignment: 'center',
              margin: [0, 20, 0, 0]
            } : {};
          };


          // Add footer with page numbers
          doc.footer = function (currentPage, pageCount) {
            var currentDate = new Date();
            var formattedDate = currentDate.toLocaleString();
            return {
              columns: [
                {
                  alignment: 'left',
                  fontSize: 9,
                  font: 'en',
                  text: ['Created on: ', { text: formattedDate }]
                },
                {
                  alignment: 'right',
                  font: 'en',
                  text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                  fontSize: 9,

                }
              ],
              margin: [10, 0, 10, 20] // [left, top, right, bottom] margins
            }



          };

        }

      },

      {
        extend: 'print',
        text: '<i class="fa fa-print"></i>',
        titleAttr: 'Print',
        title: $('.' + _dtselector).data("exportTitle"),
        customize: function (win) {

          $(win.document.body).find('th').addClass('display').css('text-align', 'center');
          $(win.document.body).find('td').addClass('display').css('text-align', 'left');
          $(win.document.body).find('table').addClass('display').css('font-size', '14px');
          // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
          $(win.document.body).find('h1').css('text-align', 'center');
        },
        exportOptions: {
          stripHtml: false,
          columns: ["thead th:not(.noExport)"],
          //code added to prevent print anchor tag href url on print
          format: {
            body: function (data, row, column, node) {
              // If cell contains an anchor tag
              if ($(node).find('a').length) {
                return $(node).find('a').text(); // Only text, no URL
              }
              return data;
            }
          }
          //code added to prevent print anchor tag href url on print
        }
      },

      {
        extend: 'colvis',
        text: '<i class="fa fa-columns"></i>',
        titleAttr: 'Columns',
        title: $('.' + _dtselector).data("exportTitle"),
        className: 'btn btn-primary colvis-left',
        postfixButtons: ['colvisRestore']
      },
    ],
    initComplete: function () {
      $('.dt-length').addClass('ss-dt-length');
      $('.dt-search input').addClass('pl-0');
    }
  });
});







/*--dropify--*/
$(document).ready(function () {
  // Basic
  $('.filestyle').dropify();

  // Translated
  $('.dropify-fr').dropify({
    messages: {
      default: 'Glissez-déposez un fichier ici ou cliquez',
      replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
      remove: 'Supprimer',
      error: 'Désolé, le fichier trop volumineux'
    }
  });

  // Used events
  var drEvent = $('#input-file-events').dropify();

  drEvent.on('dropify.beforeClear', function (event, element) {
    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });

  drEvent.on('dropify.afterClear', function (event, element) {
    alert('File deleted');
  });

  drEvent.on('dropify.errors', function (event, element) {
    console.log('Has Errors');
  });

  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('filestyle')
  $('#toggleDropify').on('click', function (e) {
    e.preventDefault();
    if (drDestroy.isDropified()) {
      drDestroy.destroy();
    } else {
      drDestroy.init();
    }
  })
});
/*--end dropify--*/

/*--nprogress--*/
$('body').show();
$('.version').text(NProgress.version);
NProgress.start();
setTimeout(function () { NProgress.done(); $('.fade').removeClass('out'); }, 1000);
/*--nprogress--*/




// initDatatable README

// _selector              // ✅ Required: CSS selector (e.g., ".table-class") targeting the <table> to initialize.

// _url                   // ✅ Required: Server-side URL (controller endpoint) from which data will be fetched via POST request.

// params = {}            // 🔧 Optional: POST parameters to send along with the AJAX request.
//                        // Example: { status: "active", department: "HR" }

// rm_export_btn = []     // 🖨️ Export buttons to remove from the DataTables Buttons extension.
//                        // Example: ["btn-copy", "btn-excel", "btn-pdf", "btn-print"]
//                        // Use ["btn-all"] to remove all export buttons.

// pageLength = 50        // 🔢 Number of rows to display per page by default.

// columnDefs = [         // 📊 Column-specific configuration:
//   {
//     targets: [-1],       // Targets the last column (use negative index for reverse)
//     orderable: false,    // Disable sorting on this column
//     className: 'dt-body-right dt-head-right' // Align content to right
//   }
// ]

// searching = true       // 🔍 Enable/disable the search box in the top-right corner of the table.

// aaSorting = []         // ↕️ Default sorting order for columns.
//                        // Format: [ [colIndex, 'asc' or 'desc'] ]
//                        // Example: [ [1, 'asc'], [3, 'desc'] ]
//                        // Leave as [] to disable default sorting.

// dataSrc = "data"       // 📦 Name of the key in the JSON response which holds the array of row data.
//                        // Example response:
//                        // {
//                        //   "status": true,
//                        //   "message": "Success",
//                        //   "data": [ { id: 1, name: "Alice" }, { id: 2, name: "Bob" } ]
//                        // }
//                        // In this case: dataSrc = "data"





function initDatatable(_selector, _url, params = {}, rm_export_btn = [], pageLength = 50, columnDefs = [
  {
    targets: [-1], // last column
    orderable: false,
    className: 'dt-body-right dt-head-right'
  }
], searching = true, aaSorting = [], dataSrc = "data", orientation = 'portrait') {





  if ($.fn.DataTable.isDataTable('.' + _selector)) { // if exist datatable it will destrory first
    $('.' + _selector).DataTable().destroy();
  }

  table = $('.' + _selector)
    .on('preInit.dt', function (e, settings) {

      var api = new $.fn.dataTable.Api(settings);
      $.each(rm_export_btn, function (key, expt_select) {
        if (expt_select === "btn-all") {
          api.buttons().remove();

        } else {
          api.buttons('.' + expt_select).remove();

        }
      });

    }).DataTable({
      dom:
        '<" dt-layout-row d-flex align-items-center justify-content-between mb-2"' +
        '<"dt-layout-cell dt-layout-start pl-0 "f>' +
        '<"dt-layout-cell dt-layout-end text-end"lB>' +
        '>' +
        'rt' +
        '<" dt-layout-row d-flex align-items-center justify-content-between mt-2"' +
        '<"dt-layout-cell dt-layout-start "i>' +
        '<"dt-layout-cell dt-layout-end  text-end"p>' +
        '>',
      buttons: [

        {
          extend: 'excel',
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: 'Excel',
          className: "btn-excel",
          title: $('.' + _selector).data("exportTitle"),
          filename: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },
        {
          extend: 'csvHtml5',
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: 'CSV',
          className: "btn-csv",
          filename: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          exportOptions: {
            columns: ["thead th:not(.noExport)"]
          }
        },
        {
          extend: 'pdf',
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: 'PDF',
          className: "btn-pdf",
          title: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          pageSize: 'A4', // Page size (A4)
          orientation: orientation,
          exportOptions: {
            stripHtml: true,
            columns: ["thead th:not(.noExport)"],
            format: {
              body: function (data, rowIdx, colIdx, node) {
                // Use node.innerText (or node.textContent) to extract only what is visible
                return node.innerText.trim();
              }
            }
          },
          customize: function (doc) {


            // Set page margins and table width
            var now = new Date();
            var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
            doc.content.splice(0, 1); // Remove default title (if any)
            var availableWidth = 595.28 - 20; // A4 width (595.28) - left margin (20) - right margin (20)
            // Add title and HR line
            doc.content.unshift(
              {
                text: $('.' + _selector).data("exportTitle"),
                alignment: 'center',
                fontSize: 14,
                bold: true,
                margin: [0, 7, 0, 5], // [left, top, right, bottom] margins
                color: '#3785e6',  // White
              }
            );

            doc.pageMargins = headerImageBase64 ? [10, 120, 10, 20] : [10, 10, 10, 20]
            // doc.pageMargins = [10, 120, 10,20]; // [left, top, right, bottom] margins
            doc.defaultStyle = {
              font: pdf_language_font,  //this it langauge to change if en ar hi 
              fontSize: 9,
              alignment: pdf_language_align
            };
            doc.styles.tableHeader = {
              alignment: pdf_language_align,
              bold: true,
              fontSize: 11
            };

            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');

            console.log(pdf_language_font);


            // Apply Devanagari font to Hindi content
            doc.content.forEach(function (item) {
              if (item.table) {
                item.table.body.forEach(function (row) {
                  row.forEach(function (cell) {
                    // Check for English text                      
                    cell.padding = [0, 0, 0, 0]; // Top, Right, Bottom, Left
                    cell.margin = [0, 0, 0, 0];
                    if (/[A-Za-z0-9]/.test(cell.text)) {
                      // If any English letter or number is found
                      cell.font = 'en';
                    } else {
                      // Pure decided language
                      cell.font = pdf_language_font;
                    }

                  });
                });
              }
            });

            // Header with image and text
            doc.header = function () {
              return headerImageBase64 ? {
                image: headerImageBase64,
                width: 575.28,
                height: 100,
                alignment: 'center',
                margin: [0, 20, 0, 0]
              } : {};
            };


            // Add footer with page numbers
            doc.footer = function (currentPage, pageCount) {
              var currentDate = new Date();
              var formattedDate = currentDate.toLocaleString();
              return {
                columns: [
                  {
                    alignment: 'left',
                    fontSize: 9,
                    font: 'en',
                    text: ['Created on: ', { text: formattedDate }]
                  },
                  {
                    alignment: 'right',
                    font: 'en',
                    text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                    fontSize: 9,

                  }
                ],
                margin: [10, 0, 10, 20] // [left, top, right, bottom] margins
              }



            };

          }

        },
        {
          extend: 'print',
          text: '<i class="fa fa-print"></i>',
          titleAttr: 'Print',
          className: "btn-print",
          title: $('.' + _selector).data("exportTitle"),
          customize: function (win) {

            if (headerImageBase64) {

              $(win.document.body).prepend(
                '<div style="text-align: center; margin-bottom: 5px;">' +
                '<img src="' + imageUrl + '" style="max-width: 100%; height: auto;">' +
                '</div>'
              );
            }


            $(win.document.body).find('th').addClass('display').css('text-align', 'center');
            $(win.document.body).find('table').addClass('display').css('font-size', '12px');
            $(win.document.body).find('td').addClass('display').css('text-align', 'left');
            $(win.document.body).find('h1').css('text-align', 'center');

            var currentDate = new Date();
            var formattedDate = currentDate.toLocaleString();

            // Add footer structure
            $(win.document.body).append(`
                      <div class="print-footer">
                        <span class="footer-left" style="float: left">${formattedDate}</span>
                        <span class="footer-right" style="float: right">Page <span class="page-number"></span> of <span class="total-pages"></span></span>
                        <div style="clear: both"></div>
                      </div>
                    `);

            // CSS for print layout
            const printStyles = `
                @page {
                  size: auto;
                  margin-bottom: 50px;
                }
                body {
                  margin-bottom: 60px !important;
                }
                .print-footer {
                  position: fixed;
                  bottom: 0;
                  width: 100%;
                  text-align: center;
                  padding: 10px;
                  background: #f1f1f1;
                }
              `;
            $(win.document.head).append(`<style>${printStyles}</style>`);

            // Calculate pages after slight delay (allows layout to complete)
            setTimeout(() => {
              const pageHeight = win.innerHeight;
              const bodyHeight = $(win.document.body).height();
              const totalPages = Math.ceil(bodyHeight / pageHeight) || 1; // Ensure at least 1 page

              $('.total-pages', win.document).text(totalPages);
              $('.page-number:first', win.document).text('1'); // First page is always 1
            }, 100);

          },
          exportOptions: {
            columns: ["thead th:not(.noExport)"]

          }

        }
      ],

      language: {
        lengthMenu: '_MENU_', // Only show the dropdown, no text
        processing: '<div class="custom-processing"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>',
        emptyTable: `<div align="center" class="dataTables_empty">No data available in table <br> <br><img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>`
      },
      "pageLength": pageLength,
      "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]],
      "searching": searching,
      "aaSorting": aaSorting, // default sorting [ [0,'asc'], [1,'asc'] ]
      "columnDefs": columnDefs, //disable sorting { "bSortable": false, "aTargets": [ 1,2 ] }
      "processing": true,
      "serverSide": true,


      "ajax": {
        "url": baseurl + _url,
        "dataSrc": dataSrc,
        "type": "POST",
        'data': params,
      }, initComplete: function () {
        $('.dt-length').addClass('ss-dt-length');
        $('.dt-search input').addClass('pl-0');
      }

    });
  // Add custom processing indicator styling
  table.on('order.dt', function () {
    // Remove previous sort classes from all headers
    $('.' + _selector + ' thead th').removeClass('padding20');

    // Get current sort info
    const order = table.order(); // e.g. [[0, 'asc'], [1, 'desc']]

    // Loop through sorted columns
    order.forEach(function ([colIndex, direction]) {
      const $th = $('.' + _selector + ' thead th').eq(colIndex);

      // Add appropriate class
      if (direction === 'asc') {
        $th.addClass('padding20');
      } else if (direction === 'desc') {
        $th.addClass('padding20');
      }
    });
  });

}



function emptyDatatable(_selector, dataSrc = "data") {
  $('.' + _selector).DataTable({
    searching: false,
    processing: true,
    paging: false,
    ordering: false,
    columnDefs: [
      {
        targets: [-1], // last column
        orderable: false,
        className: 'dt-body-right dt-head-right'
      }
    ],
    language: {
      lengthMenu: '_MENU_',
      processing: `
        <div class="custom-processing">
          <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
          <span class="sr-only">Loading...</span>
        </div>`,
      emptyTable: `<div align="center" class="dataTables_empty">No data available in table <br> <br><img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br> <span class="text-success bolds"><i class="fa fa-arrow-left"></i> Add new record or search with different criteria.</span><div></div></div>`
    },
    info: true,
    ajax: {
      url: base_url + 'backend/json-files/datatable_empty.json',
      dataSrc: dataSrc
    }
  });
}




var table_selected;

   function initDatatable_page(_selector,_url,params={},rm_export_btn=[],pageLength=50,
 columnDefs = [
  {
    targets: [-1], // last column
    orderable: false,
    className: 'dt-body-right dt-head-right'
  }
],searching=true,aaSorting=[],dataSrc="data",orientation = 'portrait'){
      
        var table_selected= $('.'+_selector).DataTable({
        // "scrollX": true,
    language: {
        lengthMenu: '_MENU_', // Only show the dropdown, no text
        processing: '<div class="custom-processing"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>',
     emptyTable: `
          <div align="center" class="dataTables_empty">
            No data available in table <br><br>
            <img src="${baseurl}backend/images/addnewitem.svg" width="150"><br><br>
            <span class="text-success bolds">
              <i class="fa fa-arrow-left"></i> Add new record or search with different criteria.
            </span>
          </div>`
      },
      
     dom:
        '<" dt-layout-row d-flex align-items-center justify-content-between mb-2"' +
        '<"dt-layout-cell dt-layout-start pl-0 "f>' +
        '<"dt-layout-cell dt-layout-end text-end"lB>' +
        '>' +
        'rt' +
        '<" dt-layout-row d-flex align-items-center justify-content-between mt-2"' +
        '<"dt-layout-cell dt-layout-start "i>' +
        '<"dt-layout-cell dt-layout-end  text-end"p>' +
        '>',
         "pageLength": 50,
         "lengthMenu": [[50, 100, 200, 500, -1], [50, 100, 200, 500, "All"]],

          buttons: [
            {
                extend:    'copy',
                text:      '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                 className: "btn-copy",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
                extend:    'excel',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                     className: "btn-excel",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
                extend:    'csv',
                text:      '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                className: "btn-csv",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
          extend: 'pdf',
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: 'PDF',
          className: "btn-pdf",
          title: $('.' + _selector).data("exportTitle") + " - " + moment().format('DD-MM-YYYYTHH_mm_ss'),
          pageSize: 'A4', // Page size (A4)
               orientation: orientation,
          exportOptions: {
            stripHtml: true,
            columns: ["thead th:not(.noExport)"],
            format: {
              body: function (data, rowIdx, colIdx, node) {
                // Use node.innerText (or node.textContent) to extract only what is visible
                return node.innerText.trim();
              }
            }
          },
          customize: function (doc) {


            // Set page margins and table width
            var now = new Date();
            var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
            doc.content.splice(0, 1); // Remove default title (if any)
            var availableWidth = 595.28 - 20; // A4 width (595.28) - left margin (20) - right margin (20)
            // Add title and HR line
            doc.content.unshift(
              {
                text: $('.' + _selector).data("exportTitle"),
                alignment: 'center',
                fontSize: 14,
                bold: true,
                margin: [0, 7, 0, 5], // [left, top, right, bottom] margins
                color: '#3785e6',  // White
              }
            );


     
            doc.pageMargins = headerImageBase64 ? [10, 120, 10, 20] : [10, 10, 10, 20]
            // doc.pageMargins = [10, 120, 10,20]; // [left, top, right, bottom] margins
            doc.defaultStyle = {
              font: pdf_language_font,  //this it langauge to change if en ar hi 
              fontSize: 9,
              alignment: pdf_language_align
            };
            doc.styles.tableHeader = {
              alignment: pdf_language_align,
              bold: true,
              fontSize: 11
            };

            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');


            // Apply Devanagari font to Hindi content
            doc.content.forEach(function (item) {
              if (item.table) {
                item.table.body.forEach(function (row) {
                  row.forEach(function (cell) {
                    // Check for English text                      
                    cell.padding = [0, 0, 0, 0]; // Top, Right, Bottom, Left
                    cell.margin = [0, 0, 0, 0];
                    if (/[A-Za-z0-9]/.test(cell.text)) {
                      // If any English letter or number is found
                      cell.font = 'en';
                    } else {
                      // Pure decided language
                      cell.font = pdf_language_font;
                    }

                  });
                });
              }
            });

            // Header with image and text
            doc.header = function () {
              return headerImageBase64 ? {
                image: headerImageBase64,
                width: 575.28,
                height: 100,
                alignment: 'center',
                margin: [0, 20, 0, 0]
              } : {};
            };


            // Add footer with page numbers
            doc.footer = function (currentPage, pageCount) {
              var currentDate = new Date();
              var formattedDate = currentDate.toLocaleString();
              return {
                columns: [
                  {
                    alignment: 'left',
                    fontSize: 9,
                    font: 'en',
                    text: ['Created on: ', { text: formattedDate }]
                  },
                  {
                    alignment: 'right',
                    font: 'en',
                    text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                    fontSize: 9,

                  }
                ],
                margin: [10, 0, 10, 20] // [left, top, right, bottom] margins
              }



            };

          }

        },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                className: "btn-print",
                title: $('.'+_selector).data("exportTitle"),
                customize: function ( win ) {

                    $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                    $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                     $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                    $(win.document.body).find('h1').css('text-align', 'center');
                },
                exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                    
                  }

            }
        ],
      
        // "scrollY":        "320px",
        "pageLength": pageLength,
        "searching": searching,
        "aaSorting": aaSorting, // default sorting [ [0,'asc'], [1,'asc'] ]
        "columnDefs": columnDefs, //disable sorting { "bSortable": false, "aTargets": [ 1,2 ] }
        "processing": true,
        "serverSide": true,

        "ajax":{
        "url": baseurl+_url,
        "dataSrc": dataSrc,
        "type": "POST",
        'data': params,
     },
       "drawCallback": function( settings ) {
      
        $('div#tab_2').html(settings.json.resultlist_view);
        // Output the data for the visible rows to the browser's console
  
    }
     
    });
    }
