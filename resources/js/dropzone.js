import Dropzone from "dropzone";

(function (cash) {
    "use strict";

    // Dropzone
    Dropzone.autoDiscover = false;
    cash(".dropzone").each(function () {
        let options = {
            url: '/dropzone',
            autoProcessQueue: false,
            addRemoveLinks: true,
            accept: (file, done) => {
                done();
            },
        };

        if (cash(this).data("single")) {
            options.maxFiles = 1;
        }

        if (cash(this).data("file-types")) {
            options.accept = (file, done) => {
                if (
                    cash(this)
                        .data("file-types")
                        .split("|")
                        .indexOf(file.type) === -1
                ) {
                    alert("Error! Files of this type are not accepted");
                    done("Error! Files of this type are not accepted");
                } else {
                    done();
                }
            };
        }

        let dz = new Dropzone(this, options);

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!");
        });
        dz.on("addedfile", function(file) {
            if (cash('#tns1-mw').length){
                cash('#tns1-mw').css('height', 'auto')
            }
        });

        if (cash(this).data("id")) {
            window[cash(this).data("id")] = dz;
        }
        
    });
})(cash);
