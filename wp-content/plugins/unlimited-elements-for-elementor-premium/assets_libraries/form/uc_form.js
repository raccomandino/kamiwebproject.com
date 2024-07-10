"use strict";

function UnlimitedElementsForm(){
  
  var t = this;
    
  /**
  * trace
  */
  function trace(str){
    console.log(str);
  }
  
  /**
  * replace fields name with its values
  */
  function replaceNamesWithValues(expr, objError){
    
    var regex = /\[(.*?)\]/g;
    var matches = expr.match(regex);
    
    if(matches)
    var values = matches.map(match => match.substring(1, match.length - 1));
    else{
      
      objError.text('Unlimited Elements Form Error: Input Name should be surrounded by square parentheses inside Formula');
      
      objError.show();
      
      throw new Error("Missing square parentheses inside Formula");
      
    }
    
    values.forEach(function(value, index){
      
      var objInpput = jQuery('.ue-input-field[name="'+value+'"]');
      
      if(!objInpput.length){
        
        objError.text('Unlimited Elements Form Error: couldn"t find Number Field Widget with name: '+value);
        
        objError.show();
        
        throw new Error("Invalid Number Field Widget Name");
        
      }
      
      if(objInpput.length > 1){
        
        objError.text('Unlimited Elements Form Error: Name option must be unique. Found '+objInpput.length+' Number Field Widgets with name: '+value);
        
        objError.show();
        
        throw new Error("Invalid Number Field Widget Name");
        
      }
      
      var inputValue = objInpput.val();
      
      //add parentheses if valus is less then 0
      if(inputValue < 0)
      inputValue = "("+inputValue+")"
      
      expr = expr.replace(value, inputValue);
      expr = expr.replace('[', '');
      expr = expr.replace(']', '');
      
    });
    
    return(expr);
    
  }
  
  /*
  * validate the expression
  */
  function validateExpression(expr){      
    
    //allow Math.something (math js operation), numbers, float numbers, math operators, dots, comas
    var allowedSymbols = /Math\.[a-zA-Z]+|\d+(?:\.\d+)?|[-+*/().,]+/g;
    
    var matches = expr.match(allowedSymbols);
    
    var result = "";
    
    if (matches) 
    result = matches.join('');    
    
    expr = result;
    
    return(expr);
    
  }
  
  /**
  * show wrong math operation error
  */
  function showWrongMathOperationError(expr, objError){
    
    objError.text(`Unlimited Elements Form Error: wrong math operation + ${expr}`);
    
    objError.show();
    
    throw new Error(`Invalid operation: ${expr}`);
    
  }
  
  /**
  * get result from expression
  */
  function getResult(expr, objError) {
    
    //hide error if visible
    objError.hide();
    
    //if space just erase it
    expr = expr.replace(/\s+/g, "");
    
    //replace inputs name with its values
    expr = replaceNamesWithValues(expr, objError);
    
    //validate espression
    expr = validateExpression(expr);
    
    var result;
    
    //catch math operation error
    try{
      result = eval(expr);
    }
    
    catch{      
      showWrongMathOperationError(expr, objError);      
    }
    
    if(isNaN(result) == true)
    showWrongMathOperationError(expr, objError);
    
    return result;
    
  }
  
  /**
  * format result number
  */
  function formatResultNumber(result, objCalcInput){
    
    var dataFormat = objCalcInput.data("format");
    
    if(dataFormat == "round")
    return(Math.round(result))
    
    if(dataFormat == "floor")
    return(Math.floor(result))
    
    if(dataFormat == "ceil")
    return(Math.ceil(result))
    
    if(dataFormat == "fractional"){
      
      var dataCharNum = objCalcInput.data("char-num");
      
      return(result.toFixed(dataCharNum))
      
    }
    
  }
  
  /**
  * init calc mode
  */
  function setResult(objCalcInput, objError){
    
    //if data formula is empty
    var dataFormula = objCalcInput.data("formula");
    
    if(dataFormula == "")
    return(false);
    
    //get result with numbers instead of fields name
    var result = getResult(dataFormula, objError);
    
    //format result
    result = formatResultNumber(result, objCalcInput);
    
    //set result to input
    objCalcInput.val(result);
    
    //set readonly attr
    objCalcInput.attr('readonly', '');
    
  }
  
  /**
  * input change controll
  */
  function onInputChange(objCalcInput){
    
    objCalcInput.trigger("input_calc");
    
  }
  
  
  /**
  * init the form
  */
  this.init = function(){
    
    //init vars
    var objCalcInputs = jQuery('.ue-input-field[data-calc-mode="true"]');
    
    //if no calc mode inpu found on page - do nothing
    if(!objCalcInputs.length)
    return(false);	
    
    //looka after each calc mode input field on a page
    objCalcInputs.each(function(){
      
      var objCalcInput = jQuery(this);
      
      //find main warapper of the widget
      var objCalcWidget = objCalcInput.parents('.ue-number');		
      var objError = objCalcWidget.find('.ue-number-error');
      
      //set result in input
      setResult(objCalcInput, objError);    
      
      //init events
      var objAllInputFields = jQuery(".ue-input-field");
      
      objAllInputFields.on('input', function(){
        onInputChange(objCalcInput)
      });
      
      //set result on custom shange event
      objAllInputFields.on('input_calc', function(){
        setResult(objCalcInput, objError)
      });
      
      //find option elements and trigger calc
      var objAllOptionFields = jQuery(".ue-option-field");
      
      objAllOptionFields.on('change', function(){
        onInputChange(objCalcInput)
      });
      
      //set result on custom change event
      objAllOptionFields.on('input_calc', function(){
        setResult(objCalcInput, objError)
      });
      
    });
    
    
  }
}

