jQuery.jlm = {
    base: '',
    params: {},
    components: {},
    controllers: {},
    templates: {},
    
    addCallback: function(controller, action, callback) {
        // Add callback to controllers hash
        if (typeof(jQuery.jlm.controllers[controller]) == 'undefined') {
            jQuery.jlm.controllers[controller] = {};
        }
        if (typeof(jQuery.jlm.controllers[controller][action]) == 'undefined') {
            jQuery.jlm.controllers[controller][action] = [];
        }

        jQuery.jlm.controllers[controller][action].push(callback);
    },

    /**
     * Bind code to a controller and it`s actions
     *
     * @param mixed routes Could be controller alone, controller.action or more of these
     *     separated by a comma. Examples: pages, pages.edit, posts.add
     * @param function callback Code that gets executed on when controller & actions is
     *     loaded
     */
    bind: function(routes, callback) {
        // Parse routes
        var routesArr = routes.split(',');
        
        jQuery.each(routesArr, function() {
            var route = jQuery.jlm.trim(this);
            var parts = route.split('.');
            
            var controller = '';
            var action = '';
            
            if (parts.length == 2) {
                // Controller & action is defined
                controller = parts[0];
                action = parts[1]; 
            } else if (parts.length == 1) {
                // Only controller defined
                controller = parts[0];
                action = '__global';
            } else {
                return alert('JLM error: Routes paramter should be in controller.action format!');
            }
            
            jQuery.jlm.addCallback(controller, action, callback);
        });
    },
    
    addComponent: function(name, object) {
        this.components[name] = object;
    },
    
    config: function(params) {
        this.base = params.base;
        this.params.controller = params.controller;
        this.params.action = params.action;
        this.params.prefix = params.prefix;
    },
    
    /**
     * Get template content by path
     * 
     * @param string templatePath
     */
    getTemplate: function(templatePath) {
        tparts = templatePath.split('/');
        var content = null;

        // default: templatePath is a template name only
        var dir = this.params.controller;
        var template = templatePath;

        // templatePath is a path
        if (tparts.length == 2) {
            dir = tparts[0];
            template = tparts[1];
        }

        if (typeof(jQuery.jlm.templates[dir]) == 'undefined') {
            return content;
        }

        if (typeof(jQuery.jlm.templates[dir][template]) !== 'undefined') {
            content = jQuery.jlm.templates[dir][template];
        }

        return content;
    },
    
    dispatch: function() {
        // Execute app_controllers beforeFilter
        if (typeof(jQuery.jlm.controllers['app_controller']) == 'object'
            && typeof(jQuery.jlm.controllers['app_controller']['beforeFilter']) == 'object') {
            jQuery.jlm.execute('app_controller', 'beforeFilter');
        }
            
        // Execute app_controllers functions bound to current action
        if (typeof(jQuery.jlm.controllers['app_controller']) == 'object'
            && typeof(jQuery.jlm.controllers['app_controller'][jQuery.jlm.params.action]) == 'object') {
            jQuery.jlm.execute('app_controller', jQuery.jlm.params.action);
        }
        
        // Execute all functions bound to current controller and action
        if (typeof(jQuery.jlm.controllers[jQuery.jlm.params.controller]) == 'object'
            && typeof(jQuery.jlm.controllers[jQuery.jlm.params.controller][jQuery.jlm.params.action]) == 'object') {
            jQuery.jlm.execute(jQuery.jlm.params.controller, jQuery.jlm.params.action);
        }
    },
    
    execute: function(controller, action) {
        jQuery.each(this.controllers[controller][action], function() {
            this();
        });
    },
    
    redirect: function(url, appendBase) {
        if (typeof(appendBase) === 'undefined') appendBase = true;
        var absUrl = url;
        if (appendBase) absUrl = jQuery.jlm.base + absUrl;
        window.location.href = absUrl;
    },
    
    /**
     * Return a parsed template filled with view variables
     * 
     * @param string templatePath
     * @param hash viewVars
     * @return string Template content
     */
    template: function(templatePath, viewVars) {
        var templateContent = this.getTemplate(templatePath);
        if (templateContent == null) {
            return null;
        }

        var template = TrimPath.parseTemplate(templateContent);

        if (typeof(viewVars) == 'undefined') {
            viewVars = {};
        }

        // BASE param for all templates
        viewVars.BASE = this.base;
        viewVars.PREFIX = this.params.prefix;

        return template.process(viewVars);
    },
    
    /**
     * Trim a string
     *
     * @link http://www.webtoolkit.info/javascript-trim.html
     *
     * @param string str String to trim
     * @param string chars Trimmed characters
     * @return string
     */
    trim: function(str, chars) {
        function ltrim(str, chars) {
            chars = chars || "\\s";
            return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
        }

        function rtrim(str, chars) {
            chars = chars || "\\s";
            return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
        }
        
        return ltrim(rtrim(str, chars), chars);
    }

};
