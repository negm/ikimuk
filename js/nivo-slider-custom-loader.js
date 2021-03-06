                                    $(document).ready(function() {
    
                                        jQuery("#slider").nivoSlider({
                                            effect:"slideInLeft",
                                            slices:15,
                                            boxCols:8,
                                            boxRows:4,
                                            animSpeed:500,
                                            pauseTime:10000,
                                            startSlide:0,
                                            directionNav:true,
                                            controlNav:true,
                                            controlNavThumbs:true,
                                            pauseOnHover:true,
                                            manualAdvance:false
                                        });

                                        $(".nivo").mouseenter(function(){
                                            $(".nivo-controlNav").stop();
                                            $(".theme-light a.nivo-nextNav").stop();
                                            $(".theme-light a.nivo-prevNav").stop();
                                            
                                            $(".theme-light a.nivo-nextNav").animate({"marginRight":"10px"},500);
                                            $(".theme-light a.nivo-prevNav").animate({"marginLeft":"10px"},500);
                                            
                                            $(".nivo-controlNav").animate({"marginTop":"396px"},500);
                                        });
                                        
                                        $(".nivo").mouseleave(function(){     
                                            $(".nivo-controlNav").stop();
                                            $(".theme-light a.nivo-nextNav").stop();
                                            $(".theme-light a.nivo-prevNav").stop();
                                            
                                            $(".theme-light a.nivo-nextNav").animate({"marginRight":"-20px"},500);
                                            $(".theme-light a.nivo-prevNav").animate({"marginLeft":"-20px"},500);
                                            $(".nivo-controlNav").animate({"marginTop":"496px"},500); 
                                        });
                                    });
                                    