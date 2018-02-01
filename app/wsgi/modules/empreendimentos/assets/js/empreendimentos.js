/**
 * Created by rodri on 27/12/2017.
 */
$(window).load(function() {

    if ($("#EdEmpreendimentoId").val() == undefined)
        return false;
    else {
        request('onGraficoLotes', {
            data: {
                EdEmpreendimentoId: $("#EdEmpreendimentoId").val()
            },
            success: function (response) {
                // console.log(response);
                GraficoLotes(response);
            }
        });
    }
});

$(document).ready(function() {

    //Inicializa os anexos
    //$("#lightgallery").lightGallery();

});

function GraficoLotes(dados) {

      //Grafico por midia
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: dados.LabelLotes,
            datasets: [{
                label: 'Lotes vendidos',
                data: dados.DataLotes,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        // options: {
        //     scales: {
        //         yAxes: [{
        //             ticks: {
        //                 //beginAtZero:true
        //             }
        //         }]
        //     }
        // }
    });

}


Dropzone.autoDiscover = false;

$("div#mydropzoneEmpreendimento").dropzone({
    url: "/sistema/empreendimentos/postimg",
    addRemoveLinks : true,
    maxFilesize: 10.5,
    dictDefaultMessage: '<span class="text-center"><span class="font-lg visible-xs-block visible-sm-block visible-lg-block"><span class="font-lg"><i class="fa fa-caret-right text-danger"></i> Arraste e solte os arquivos aqui para enviar<span class="font-xs"></span></span><span>&nbsp&nbsp<h4 class="display-inline"> (ou clique)</h4></span>',
    dictResponseError: 'Error uploading file!',
    dictRemoveFile: 'Remover arquivo',
    dictCancelUpload: 'Cancelar upload',
    init: function() {
        this.on("sending", function(file, xhr, formData){
            formData.append("EmpreendimentoId", $("#EdEmpreendimentoId").val());
        });
    },
    success: function (file, response) {
        var imgName = response;
        file.previewElement.classList.add("dz-success");
        console.log("Successfully uploaded :" + imgName);
    },
    error: function (file, response) {
        file.previewElement.classList.add("dz-error");
        console.log(file);
        console.log(response);
    }
});