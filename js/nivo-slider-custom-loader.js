                                    $(document).ready(function() {
    
                                        jQuery("#slider").nivoSlider({
                                            effect:"fold",
                                            slices:15,
                                            boxCols:8,
                                            boxRows:4,
                                            animSpeed:300,
                                            pauseTime:5000,
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
                                            
                                            $(".theme-light a.nivo-nextNav").animate({"marginRight":"10px"},1000);
                                            $(".theme-light a.nivo-prevNav").animate({"marginLeft":"10px"},1000);
                                            
                                            $(".nivo-controlNav").animate({"marginTop":"396px"},1000);
                                        });
                                        
                                        $(".nivo").mouseleave(function(){     
                                            $(".nivo-controlNav").stop();
                                            $(".theme-light a.nivo-nextNav").stop();
                                            $(".theme-light a.nivo-prevNav").stop();
                                            
                                            $(".theme-light a.nivo-nextNav").animate({"marginRight":"-20px"},1000);
                                            $(".theme-light a.nivo-prevNav").animate({"marginLeft":"-20px"},1000);
                                            $(".nivo-controlNav").animate({"marginTop":"496px"},1000); 
                                        });
                                    });
                                    