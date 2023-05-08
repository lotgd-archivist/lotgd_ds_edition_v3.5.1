atrajQ(document).ready(function() {
    var form,dialog,start,end,current_event;

    atrajQ(document).on("mouseover", ".qtip-grpuser", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });

    atrajQ(document).on("mouseover", ".eventleave", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });

    atrajQ(document).on("mouseover", ".eventjoin", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });

    atrajQ(document).on("click touchstart", ".eventjoin", function (event) {
        var ob = atrajQ(this);
        atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickje", {id: ob.data('gid')}, function (data) {});
        ob.find('i').removeClass('fa-close');
        ob.find('i').addClass('fa-check');
        ob.removeClass('eventjoin');
        ob.addClass('eventleave');
    });

    atrajQ(document).on("click touchstart", ".eventleave", function (event) {
        var ob = atrajQ(this);
        atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickle", {id: ob.data('gid')}, function (data) {});
        ob.find('i').removeClass('fa-check');
        ob.find('i').addClass('fa-close');
        ob.removeClass('eventleave');
        ob.addClass('eventjoin');
    });

    atrajQ(document).on("mouseover", ".cursor_active", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });

    atrajQ(document).on("mouseover", ".cursor_inactive", function (event) {
        atrajQ(this).css("cursor", "default");
    });

    atrajQ('.qtip-grpuser').each(function() {
        atrajQ(this).qtip({
            content: {
                text: function(event, api) {
                    return atrajQ.ajax({
                        url: '/bathorys_popups.php?mod=calendar&ajax=true&grpuser='+api.elements.target.data('gid')
                    })
                        .then(function(content) {
                            return content
                        }, function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        });
                }
            },
            show: {
                solo: true
            },
            hide: {
                distance: 30
            },
            position: {
                target: 'mouse',
                adjust: { x: 10, y: 10 },
                viewport: atrajQ(window)
            },
            style: {
                classes: 'qtip-tipsy'
            }
        });
    });

    atrajQ(document).on("mouseover", ".qtip-eventuser", function (event) {
        atrajQ(this).css("cursor", "pointer");
    });

    atrajQ('.qtip-eventuser').each(function() {
        atrajQ(this).qtip({
            content: {
                text: function(event, api) {
                    return atrajQ.ajax({
                        url: '/bathorys_popups.php?mod=calendar&ajax=true&eventuser='+api.elements.target.data('gid')
                    })
                        .then(function(content) {
                            return content
                        }, function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        });
                }
            },
            show: {
                solo: true
            },
            hide: {
                distance: 30
            },
            position: {
                target: 'mouse',
                adjust: { x: 10, y: 10 },
                viewport: atrajQ(window)
            },
            style: {
                classes: 'qtip-tipsy'
            }
        });
    });


    function addEvent() {
        var valid = true;
        if(atrajQ('#title').val() != ''){
            atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickpriv", atrajQ('#theform').serialize(), function (data) {
                atrajQ('#calendar').fullCalendar('refetchEvents');
                atrajQ('#calendar').fullCalendar('rerenderEvents');
            });
            dialog.dialog( "close" );
        }else{
            alert('Bitte gebe einen Titel ein!');
        }
        return valid;
    }
    dialog = atrajQ( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 490,
        width: 550,
        modal: true,
        buttons: {
            "Speichern": addEvent,
            "Abbrechen": function() {
                dialog.dialog( "close" );
            }
        },
        close: function() {
            atrajQ('#id').val('');
            form[0].reset();
        }
    });
    form = dialog.find( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        addEvent();
    });
    var currentCal = 'privgrp';
    atrajQ('#cal-selector').on('change', function() {
        currentCal = this.value || false;
        atrajQ('#calendar').fullCalendar('destroy');
        renderCalendar();
    });
    function renderCalendar() {
        atrajQ('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'de',
            height: 480,
            contentHeight: 480,
            buttonIcons: false,
            weekNumbers: true,

            selectable: true,
            selectHelper: true,
            select: function(s, e) {
                start = s;
                end = e;
                atrajQ('#start_date').val(s.format('YYYY-MM-DD HH:mm'));
                atrajQ('#end_date').val(e.format('YYYY-MM-DD HH:mm'));
                dialog.dialog( "open" );
                atrajQ('#calendar').fullCalendar('unselect');
            },

            editable: false,
            eventDrop: function(event, delta, revertFunc) {
                if(event.isOwner && !event.recuring){
                    var ed = (event.end != null) ? event.end.format() : '';
                    atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickdrop", {id: event.id, start: event.start.format(), end: ed}, function (data) {
                    });
                }else{
                    revertFunc();
                }
            },
            eventResize: function(event, delta, revertFunc) {
                if(event.isOwner && !event.recuring){
                    var ed = (event.end != null) ? event.end.format() : '';
                    atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickdrop", {id: event.id, start: event.start.format(), end: ed}, function (data) {
                    });
                }else{
                    revertFunc();
                }
            },
            eventClick: function(event, jsEvent, view) {
                var now = moment();
               if(!event.isOwner && event.start > now){
                   if(event.teil){
                       atrajQ.confirm({
                           icon: 'fa fa-close',
                           title: 'Teilnahme',
                           content: 'Willst du die Teilnahme absagen?',
                           confirmButton: 'Ja, absagen!',
                           cancelButton: 'Niemals!',
                           columnClass: 'col-confirm',
                           theme: 'holodark',
                           confirm: function(){
                               atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickle", {id: event.id}, function (data) {});
                               event.teil = 0;
                               atrajQ('#calendar').fullCalendar('rerenderEvents');
                           }
                       });
                   }else{
                       atrajQ.confirm({
                           icon: 'fa fa-check',
                           title: 'Teilnahme',
                           content: 'Willst du teilnehmen?',
                           confirmButton: 'Ja, teilnehmen!',
                           cancelButton: 'Ach nöööööö...',
                           columnClass: 'col-confirm',
                           theme: 'holodark',
                           confirm: function(){
                               atrajQ.post("./bathorys_popups.php?mod=calendar&ajax=true&do=quickje", {id: event.id}, function (data) {});
                               event.teil = 1;
                               atrajQ('#calendar').fullCalendar('rerenderEvents');
                           }
                       });
                   }
                }

                if(event.isOwner){
                    current_event = event;
                    atrajQ('#id').val(event.id);
                    atrajQ('#title').val(event.title_original);
                    atrajQ('#description').val(event.description_original);
                    atrajQ('#color').val(event.color);
                    atrajQ('#textColor').val(event.textColor);

                    atrajQ('#color').minicolors('value', event.color);
                    atrajQ('#textColor').minicolors('value', event.textColor);

                    atrajQ('#groupid').val(event.groupid);
                    atrajQ('#private').val(event.private);
                    atrajQ('#recuring').val(event.recuring);

                    atrajQ('#start_date').val(event.start.format('YYYY-MM-DD HH:mm'));
                    atrajQ('#end_date').val(event.end.format('YYYY-MM-DD HH:mm'));
                    dialog.dialog( "open" );
               }
            },
            eventLimit: true,
            events: '/bathorys_popups.php?mod=calendar&ajax=true&get='+currentCal,
            eventRender: function(event, element) {
                var now = moment();
                element.find('.fc-content').html( ( event.isOwner ? '<i class="fa fa-user"></i>' : (  ( (event.teil) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>')  ) ) + '<span style="margin:0 5px 0 5px; border-right: 1px solid '+event.textColor+'"></span>' + element.find('.fc-content').html() );
                element.find('.fc-title').html( event.title );
                element.addClass(( (event.start > now || event.isOwner) ? 'cursor_active' : 'cursor_inactive' ));
               if(event.title)
               {
                   element.qtip({
                       show: {
                           solo: true
                       },
                       position: {
                           target: 'mouse',
                           adjust: { x: 10, y: 10 },
                           viewport: atrajQ(window)
                       },
                       style: {
                           classes: 'qtip-tipsy'
                       },
                       content: {
                           text: '<div class="event_title">' + (event.title ? event.title : '') + ( (event.private == 1) ? ' <i class="fa fa-eye fa-lg event_pull_right"></i>' : ' <i class="fa fa-globe fa-lg event_pull_right"></i>' )
                           + '</div><div class="event_time event_clear">Beginn: '+event.start.format('YYYY-MM-DD HH:mm')
                           +( (event.end != null) ? '<br>&nbsp;&nbsp;&nbsp;&nbsp;Ende: '+event.end.format('YYYY-MM-DD HH:mm') : '' )
                           +'</div><div class="event_author">'
                           + 'Ersteller: '+( event.name ? event.name : 'Unbekannt')
                           +'</div><div class="event_group">'
                           + 'Gruppe: '+( event.group ? event.group : 'Keine')
                           +'</div><div class="event_description">'
                           + (event.description ? event.description : '')
                           +'</div>'+( (event.user) ? '<div class="event_user">Teilnehmer: <br>'+(event.user ? event.user : '') +'</div>' : '' )
                       }
                   });
               }
            }
        });
    }
    renderCalendar();
});
