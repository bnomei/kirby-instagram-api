
InstagramAPI = ($, $field) ->
  
  self = this

  this.class           = '.instagramapidata'
  this.$field          = $field
  this.$instagramapi   = $field.find(self.class)
  this.$button         = $field.find(self.class + '-button a')
  this.$text           = $field.find(self.class + '-button a span')
  this.delay           = 2000 #ms
  this.l = {
    default : this.$text.html()
    # progress: this.$text.attr '...'
  }

  this.parseResult = (result) ->
    if result['code'] is 200
      self.hasSuccess result['message']
    else
      self.hasError result.error, result['message']
    return

  this.hasError = (error, msg) ->
    if msg == undefined then msg = self.l.error
    if msg == undefined or msg.length == 0 then msg = error

    self.$button.addClass('btn-negative')
      .children('span').text msg
    setTimeout ->
      self.$button.removeClass('btn-negative')
      self.$button.children('span').text self.l.default
    , self.delay
    return

  this.hasSuccess = (msg) ->
    if msg == undefined then msg = self.l.success

    self.$button.addClass('btn-positive')
    self.$button.children('span').text msg
    
    setTimeout  ->
      self.$button.removeClass('btn-positive')
      self.$button.children('span').text self.l.default
    , self.delay

    return

  this.fieldValue = ->
    v = $(self.class + '-field').children('input').val().trim()
    account = if v.length > 0 then v.split(' ')[0] else undefined
    return account

  this.init = ->
    #console.log self.fieldValue()
    return

  # register to click event
  # on click open url using ajax
  this.$field.find(self.class + '-button a').click (ev) ->
    ev.preventDefault()
    url = $(this).attr('href') + '/ajax'
    $.fn.InstagramAPIAjax self, url
    return

  return this.init()

#######################################@@
# jQuery
(($) ->
  $.fn.InstagramAPIAjax = (instagramapi, url) ->
    if instagramapi.$field == undefined
      return

    if instagramapi.$field.hasClass 'ajax'
      return

    instagramapi.$field.addClass 'ajax'
    document.body.style.cursor = 'wait'
    #instagramapi.$button.children('span').text instagramapi.l.progress

    $.ajax {
      url: url
      type: 'GET'
      success: (result) ->
        instagramapi.parseResult result

      error: (jqXHR, textStatus, errorThrown) ->
        instagramapi.hasError textStatus + errorThrown

      complete: ->
        instagramapi.$field.removeClass 'ajax'
        document.body.style.cursor = 'default'
    }

    return # fn.ajax

  #######################################
  # Hook into panel initialization.
  $.fn.instagramapidata = -> # NOTE: lower- or uppercase __does__ matter for kirby!
    return new InstagramAPI($, this)

) jQuery
