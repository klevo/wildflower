var Controller = jQuery.Class.create({
    layout: 'default',
    viewVars: {},
	
	init: function() {
		// Load components
		for (var i in this.components) {
			var name = this.components[i];
			var className = name + 'Component';
			this[className] = new window[className](this);
		}
	},
	
	afterRender: function() {
	},
    
	beforeFilter: function() {
    },
	
    beforeRender: function() {
    },
	
	/**
	 * Return element's HTML
	 * 
	 * @param string templatePath Template path in a form of string 'pages/home', 'elements/dialog', ...
	 * @param array viewVars
	 * @return string
	 */
	element: function(element, viewVars) {
        var templateContent = this.getTemplate('elements/' + element);
		if (templateContent == null) {
            return;
        }
		
		var template = TrimPath.parseTemplate(templateContent);
        var elementHtml = template.process(viewVars);
		
		return elementHtml;
	},
	
    /**
     * Execute a controller's action
     * 
     * @param {Object} args
     */
    execute: function(args) {
        if (typeof(args.controller) === 'undefined') {
            args.controller = this.name;
        }
        
        this.dispatcher.dispatch(args);         
    },
    
    /**
     * Render a template inside a containing element
     * 
     * @param string templatePath Path or name to a template: admin_index, elements/dialog...
     * @param string container Selector of the element the template will be rendered into
     */
    render: function(templatePath, container) {
        if (typeof(container) === 'undefined') {
            container = 'body';
        }
        
		var templateContent = this.getTemplate(templatePath);
		if (templateContent == null) {
			return;
		}
        
        var template = TrimPath.parseTemplate(templateContent);
        var content_for_layout = template.process(this.viewVars);
        
		// @TODO layout is in JLM_TEMPLATES
        var layoutName = 'Layouts' + ucwords(this.layout) + 'Template';
        var layout = TrimPath.parseTemplate(eval(layoutName));
        var dataForLayout = { 'content_for_layout': content_for_layout };
        var html = layout.process(dataForLayout);
        
        $(container).empty().append(html);
    },
	
	/**
	 * Calls a controllers action with all callbacks
	 * 
	 * @todo Move to dispatcher?
	 * @param string actionName
	 */
	runAction: function(actionName) {
		log('JLM: Calling Controller::runAction(' + actionName + ')');
		
		this.beforeFilter();
		
		var launchAction = 'this.' + actionName + '();';
		if (typeof this[actionName] ==  'function') {
			this[actionName]();
		}
		
		this.beforeRender();
		this.render(actionName);
		this.afterRender();
	},
    
    /**
     * Set variables for the template
     * 
     * @param {Object} varsHash
     */
    set: function(varsHash) {
        this.viewVars = jQuery.extend(this.viewVars, varsHash);
    },
	
	// Private methods:
	
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
			
		if (typeof(JLM_TEMPLATES[dir]) == 'undefined') {
			return content;
		}
		
        if (typeof(JLM_TEMPLATES[dir][template]) !== 'undefined') {
			content = JLM_TEMPLATES[dir][template];
		}
		
		return content;
	},
	
	/**
	 * Return a parsed template filled with view variables
	 * 
	 * @param string templatePath
	 * @param {Object} viewVars
	 */
	parseTemplate: function(templatePath, viewVars) {
		var templateContent = this.getTemplate(templatePath);
        if (templateContent == null) {
            return;
        }
        
        var template = TrimPath.parseTemplate(templateContent);
		
		if (typeof(viewVars) == 'undefined') {
			viewVars = {};
		}
		
		viewVars.BASE = BASE;
		
        var html = template.process(viewVars);
        return html;
	},
	
	/**
	 * Redirect browser to a specified url
	 * 
	 * @param string url
	 * 
	 * @TODO allow hash URLs
	 */
	redirect: function(url, useBase) {
		if (useBase !== false) {
			url = BASE + url;
		}
		window.location.href = url;
	}
	
});
