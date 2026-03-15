
// Define fonts before initializing DataTable
pdfMake.fonts = {
  Roboto: {
    normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Regular.ttf',
    bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Medium.ttf',
    italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-Italic.ttf',
    bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/fonts/Roboto/Roboto-MediumItalic.ttf'
  },
  ar: {
    normal: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Regular.ttf',
    bold: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Bold.ttf',
    italics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Regular.ttf',
    bolditalics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansArabic/NotoSansArabic-Bold.ttf'
  },
  hi: {
    normal: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Regular.ttf',
    bold: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Bold.ttf',
    italics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Regular.ttf',
    bolditalics: 'https://cdn.jsdelivr.net/gh/googlefonts/noto-fonts@main/hinted/ttf/NotoSansDevanagari/NotoSansDevanagari-Bold.ttf'
  },
};


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



$(document).ready(function () {

  var table = $('.example').DataTable({
    "aaSorting": [],
    rowReorder: {
      selector: 'td:nth-child(2)'
    },
    responsive: 'true',
    dom: "Bfrtip",
    buttons: [

      {
        extend: 'copyHtml5',
        text: '<i class="fa fa-files-o"></i>',
        titleAttr: 'Copy',
        title: $('.download_label').html(),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },

      {
        extend: 'excelHtml5',
        text: '<i class="fa fa-file-excel-o"></i>',
        titleAttr: 'Excel',

        title: $('.download_label').html(),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },

      {
        extend: 'csvHtml5',
        text: '<i class="fa fa-file-text-o"></i>',
        titleAttr: 'CSV',
        title: $('.download_label').html(),
        exportOptions: {
          columns: ["thead th:not(.noExport)"]
        }
      },



      {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf-o"></i>',
        titleAttr: 'PDF',
        className: "btn-pdf",
        title: $('.download_label').html(),
        exportOptions: {

          columns: ["thead th:not(.noExport)"]
        },

      },


      {
        extend: 'print',
        text: '<i class="fa fa-print"></i>',
        titleAttr: 'Print',
        title: $('.download_label').html(),
        customize: function (win) {

          $(win.document.body).find('th').addClass('display').css('text-align', 'center');
          $(win.document.body).find('td').addClass('display').css('text-align', 'left');
          $(win.document.body).find('table').addClass('display').css('font-size', '14px');
          // $(win.document.body).find('table').addClass('display').css('text-align', 'center');
          $(win.document.body).find('h1').css('text-align', 'center');
        },
        exportOptions: {
          stripHtml: false,
          columns: ["thead th:not(.noExport)"]
        }
      },

      {
        extend: 'colvis',
        text: '<i class="fa fa-columns"></i>',
        titleAttr: 'Columns',
        title: $('.download_label').html(),
        postfixButtons: ['colvisRestore']
      },
    ]
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
// _selector, // selector  class of table
// _url, // url is url of controller where data to be fetch
// params={}, is parameter of post method
// rm_export_btn=[], // var rm_export_btn = ["btn-pdf"] //"btn-copy","btn-excel","btn-csv","btn-pdf","btn-print" // btn-all
// pageLength=100, //per page data
// aoColumnDefs=[{ "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}],
// searching=true,
// aaSorting=[],
// dataSrc="data" it is array source of data


function initDatatable(_selector, _url, params = {}, rm_export_btn = [], pageLength = 100, aoColumnDefs = [{ "bSortable": false, "aTargets": [-1], 'sClass': 'dt-body-right' }], searching = true, aaSorting = [], dataSrc = "data") {
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

      layout: {
        topStart: {

          buttons: [

            {
              extend: 'excel',
              text: '<i class="fa fa-file-excel-o"></i>',
              titleAttr: 'Excel',
              className: "btn-excel",
              title: $('.' + _selector).data("exportTitle"),
              exportOptions: {
                columns: ["thead th:not(.noExport)"]
              }
            },
            {
              extend: 'csv',
              text: '<i class="fa fa-file-text-o"></i>',
              titleAttr: 'CSV',
              className: "btn-csv",
              title: $('.' + _selector).data("exportTitle"),
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
              exportOptions: {
                columns: ["thead th:not(.noExport)"]
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
                  font: language_short_code,
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
                        if (/^[A-Za-z0-9 ]+$/.test(cell.text)) { 
                          cell.font = 'hi'; 
                          cell.alignment = pdf_language_align;
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
          ]

        }
      },

      language: {
        processing: '<div class="custom-processing"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
      },
      "pageLength": pageLength,
      "searching": searching,
      "aaSorting": aaSorting, // default sorting [ [0,'asc'], [1,'asc'] ]
      "aoColumnDefs": aoColumnDefs, //disable sorting { "bSortable": false, "aTargets": [ 1,2 ] }
      "processing": true,
      "serverSide": true,


      "ajax": {
        "url": baseurl + _url,
        "dataSrc": dataSrc,
        "type": "POST",
        'data': params,
      }

    });
  // Add custom processing indicator styling



}




function emptyDatatable(_selector, dataSrc = "data") {

  $('.' + _selector).DataTable({
    "searching": false,
    "processing": true,
    "paging": false,
    "ordering": false,
    "info": true,
    "ajax": {
      "url": base_url + 'backend/json-files/datatable_empty.json',
      "dataSrc": dataSrc
    }
  });


}