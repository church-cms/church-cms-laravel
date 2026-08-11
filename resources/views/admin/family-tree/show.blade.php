@extends('layouts.admin.layout')
@section('content')
    <div>
        <h1 class="admin-h1 my-3">Family Tree</h1>
        <div id="ochart"></div>
    </div>
@endsection
@push('scripts')
    <style>
        /*.dtree-wrapper *{
      box-sizing: border-box
    }*/
        .dtree-wrapper {
            position: relative;
            text-align: center;
            overflow: auto;
            padding: 10px;
            transition: 0.3s all ease;
        }

        .dtree-node-wrapper {}

        .dtree-node-cell {
            position: relative;
            text-align: center;
            z-index: 8;
            display: inline-block;
            white-space: nowrap;
            vertical-align: top;
            transition: 0.3s all ease;
        }

        .dtree-node {
            text-align: center;
            display: inline-block;
            padding: 10px 10px 20px 10px;
            box-shadow: 0px 0px 8px -2px #888;
            min-width: 225px;
        }

        .dtree-node-main {
            width: 100%;
            position: relative;
            padding-top: 1px;
        }

        .dtree-node .dtree-img img {
            max-width: 100%;
            max-width: 60px;
            border-radius: 50px;
            font-size: 0;
        }

        .dtree-node .dtree-branch {
            position: absolute;
            height: 1px;
            z-index: -1;
        }

        .dtree-node .dtree-branch.linex {
            width: 50%;
            right: 0;
            bottom: calc(100% - 1px);
            border-top: 1px solid #a0aec0;

        }

        .dtree-node .dtree-branch.liney {
            top: 0;
            height: 100%;
            left: 50%;
            width: 1px;
            border-left: 1px solid #a0aec0;
        }

        .dtree-child-container {
            position: relative;
            overflow: hidden;
            transition: 0.3s all ease;
            /*height: 100%;*/
        }

        .node-collapse {
            position: absolute;
            bottom: 0;
            background: white;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 50%;
            left: calc(50% - 15px);
            width: 30px;
            height: 30px;
            font-size: 30px;
            line-height: 25px;
            cursor: pointer;
        }

        .node-collapse:after {
            content: "-";
        }

        .dtree-collapsed .node-collapse:after {
            content: "+";
        }

        .dtree-collapsed.dtree-node .dtree-branch.liney {
            height: 50% !important;
        }

        .dtree-target-collapsed {
            opacity: 0;
            width: 0;
            height: 0;
            transform: scale(0) translateY(-100%)
        }

        .dtree-x .dtree-target-collapsed {
            transform: scale(0) translateX(-100%)
        }

        .dtree-x .dtree-node-cell {
            text-align: left;
            display: block;
        }

        .dtree-x .dtree-child-container,
        .dtree-x .dtree-node-main {
            display: inline-block;
            vertical-align: middle;
            width: auto;
        }

        .dtree-x .dtree-branch.linex {
            position: absolute;
            height: 1px;
            z-index: -1;
            right: auto;
            top: auto;
            left: 0;
            border: none;
            border-left: 1px solid #777;
        }

        .dtree-x .dtree-node .dtree-branch.liney {
            width: 100%;
            top: 50%;
            left: 0;
            border: none;
            border-top: 1px solid #777;
        }

        .dtree-x .node-collapse {
            bottom: auto;
            left: auto;
            right: 10px;
            top: calc(50% - 15px);
        }

        .dtree-x .dtree-collapsed.dtree-node .dtree-branch.liney {
            height: auto !important;
            width: 50% !important;
        }

        /* basic template*/
        .dtree-img,
        .dtree-name {
            display: inline-block;
            vertical-align: middle;
        }

        .dtree-node {
            background: white;
            border: 1px solid #ccc;
            border-top: 2px solid #2196f3;
            box-shadow: 0px 1px 10px -4px #999;
        }

        .dtree-name {
            font-size: 16px;
            font-weight: 600;
            width: calc(100% - 90px);
            padding: 10px;
            text-transform: capitalize;
            text-align: left
        }

        .dtree-name .sub {
            display: block;
            font-weight: 400;
            color: #555;
            font-size: 13px;
        }

        body {
            overflow-x: hidden;
        }

    </style>
    <script>
        /*  plugin - dtree
        version - 1.0.0
    */

        (function($) {
            $.fn.dtree = function(options) {
                var $this = this,
                    settings = $.extend({
                        isHorizontal: false,
                        customTemplate: false,
                        gutter: 20,
                        zoom: true,
                        placeholderImg: "https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg",
                        isCollapsible: true,
                        searchbox: true
                    }, options),
                    _parents = [],
                    _nodeDims = {},
                    _iniScale = 0,
                    _domNodes = {},
                    _dtreeID = $this.attr("id") || "dtree" + Math.round(Math.random() * 100),
                    init = {
                        logger: function() {
                            _parents = calc.getParentChildObj();
                            _nodeDims = init.getNodeDimensions();

                            $this.html(init.buildNodes(_nodeDims));
                            init.rearrangeDom();
                            init.setSelectors();
                            console.log(settings.nodes, _nodeDims, _parents);
                        },
                        buildNodes: function(nodeDims) {
                            if (settings.customTemplate) {

                            } else {
                                var activepid = _parents.rootNode.id,
                                    pNodes = _parents.listParents.length,
                                    ndPipe = [activepid],
                                    nodeHTML = "<div class=\"dtree-wrapper " + (settings.isHorizontal ?
                                        'dtree-x' : '') + "\">";

                                /*  if (settings.searchbox) {
                                      nodeHTML += "<div class=\"dtree-searchbox\"><input type=\"text\" class=\"dtree-search-control\"><i class=\"dtree-search-icon\"></i>" +
                                          "<ul class=\"dtree-search-list\"></ul>" +
                                          "</div>";
                                  }*/


                                nodeHTML += "<div class=\"dtree-node-wrapper\" id=\"" + _dtreeID +
                                    "_node_0\" style=\"width:100%\">" +
                                    "<div class=\"dtree-node-cell\">" +
                                    "<div class=\"dtree-node-main\">" +
                                    "<div class=\"dtree-node \" style=\"margin:" + settings.gutter + "px\">" +
                                    "<div class=\"dtree-img\"><img src=\"" + (_parents.rootNode.img || settings
                                        .placeholderImg) + "\"></div>" +
                                    "<div class=\"dtree-name\">" + _parents.rootNode.name +
                                    "<span class=\"sub\">" + _parents.rootNode.txt + "</span></div>" +
                                    (settings.isCollapsible ?
                                        "<div class=\"node-collapse\" data-dtree-collpase-node=\"#" + _dtreeID +
                                        "_node_" + activepid + "\"  style=\"bottom : " + settings.gutter / 2 +
                                        "px\"></div>" : "");
                                nodeHTML += (settings.isHorizontal) ?
                                    "<i class=\"dtree-branch liney\" style=\"height:50%; top:50%; left: " +
                                    settings.gutter + "px; width: calc(100% - " + settings.gutter +
                                    "px)\"></i>" :
                                    "<i class=\"dtree-branch liney\" style=\"width:50%; left:50%; top: " +
                                    settings.gutter + "px; height: calc(100% - " + settings.gutter +
                                    "px)\"></i>";
                                nodeHTML += "</div></div><div class=\"dtree-child-container\" id=\"" +
                                    _dtreeID + "_node_" + activepid + "\"></div></div></div>";

                                while (pNodes && ndPipe.length) {
                                    activepid = ndPipe.shift();
                                    var childNdsObj = _parents.parentObj[_parents.listParents.indexOf(
                                        activepid)];
                                    if (!childNdsObj) {
                                        continue;
                                    }
                                    var childNds = childNdsObj.childNodes;

                                    nodeHTML += "<div class=\"dtree-node-wrapper \" id=\"" + _dtreeID +
                                        "_parent_" + activepid + "\"  >";

                                    for (var i = 0; i < childNds.length; i++) {
                                        var isParent = (_parents.listParents.indexOf(childNds[i].id) !== -1),
                                            hasSiblings = childNds.length - 1,
                                            xRight = (i == 0) ? "0" : "50%",
                                            xWidth = "50%",
                                            isMiddle = (i > 0 && i < childNds.length - 1),
                                            imgSrc = childNds[i].img || settings.placeholderImg,
                                            parObj = _parents.parentObj[_parents.listParents.indexOf(childNds[i]
                                                .id)],
                                            isCollapsedNode = childNds[i].isCollapsed;

                                        if (isMiddle) {
                                            xWidth = "100%";
                                            xRight = "0";
                                        }
                                        settings.nodes[_parents.nodeById[childNds[i].id]].lvl = 1 + settings
                                            .nodes[_parents.nodeById[activepid]].lvl;
                                        // width: 100/childNds.length
                                        nodeHTML += "<div class=\"dtree-node-cell \"  >" +
                                            "<div class=\"dtree-node-main\">" +
                                            "<div class=\"dtree-node " + (isCollapsedNode &&
                                            "dtree-collapsed") + "\" style=\"margin:" + settings.gutter +
                                            "px\">" +
                                            "<div class=\"dtree-img\"><img src=\"" + imgSrc + "\"></div>" +
                                            "<div class=\"dtree-name\">" + childNds[i].name +
                                            "<span class=\"sub\">" + childNds[i].txt + "</span></div>";
                                        nodeHTML += isParent ? (settings.isCollapsible ?
                                            "<div class=\"node-collapse\" data-dtree-collpase-node=\"#" +
                                            _dtreeID + "_node_" + childNds[i].id + "\" style=\"bottom : " +
                                            settings.gutter / 2 + "px\"></div>" : "") : "";

                                        if (settings.isHorizontal) {
                                            nodeHTML += (!isParent && parObj ? (parObj.childNodes.length > 1) :
                                                    parObj) ? "<i class=\"dtree-branch liney\" ></i>" :
                                                "<i class=\"dtree-branch liney\" style=\"width:calc(100% - " +
                                                settings.gutter + "px); left: 0\"></i>";

                                        } else {
                                            nodeHTML += (!isParent && parObj ? (parObj.childNodes.length > 1) :
                                                    parObj) ? "<i class=\"dtree-branch liney\"></i>" :
                                                "<i class=\"dtree-branch liney\" style=\"height:calc(100% - " +
                                                settings.gutter + "px); top: 0\"></i>";
                                            nodeHTML += hasSiblings ?
                                                "<i class=\"dtree-branch linex\" style=\"right: " + xRight +
                                                "; width: " + xWidth + "\"></i>" : "";
                                        }

                                        nodeHTML += "</div></div>" +
                                            ((hasSiblings && (settings.isHorizontal)) ?
                                                "<i class=\"dtree-branch linex\" style=\"bottom: " + xRight +
                                                "; height: " + xWidth + "\"></i>" : "") +
                                            "<div class=\"dtree-child-container  " + (isCollapsedNode &&
                                                "dtree-target-collapsed") + "\" id=\"" + _dtreeID + "_node_" +
                                            childNds[i].id + "\"></div></div>";
                                        ndPipe.push(childNds[i].id);
                                    }
                                    nodeHTML += "</div>";
                                    pNodes--;
                                }


                                return nodeHTML + "</div>";
                            }
                        },
                        rearrangeDom: function() {
                            var $parents = $this.find(".dtree-node-wrapper").not("#" + _dtreeID + "_node_0"),
                                $childs = $this.find(".dtree-child-container");

                            $parents.each(function() {
                                var custNodeId = $(this).attr("id").split("_"),
                                    cutNode = $(this).detach(),
                                    pasteNode = $childs.filter("#" + _dtreeID + "_node_" + custNodeId[
                                        custNodeId.length - 1]);
                                if (pasteNode) {
                                    cutNode.appendTo(pasteNode);
                                }
                            });
                        },
                        getNodeDimensions: function() {
                            $('body').append(
                                "<div id=\"tempNode\"><div class=\"dtree-node\"><div class=\"dtree-img\"><img src=\"https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg\"></div> <div class=\"dtree-name\">mmmmmmmmmm</div><span class=\"dtree-branch\"><i></i><i></i></span></div></div>"
                                );
                            var $tempNode = $("#tempNode");
                            var tw = $tempNode.children().eq(0).outerWidth(true),
                                th = $tempNode.children().eq(0).outerHeight(true);

                            $tempNode.remove();
                            return {
                                nodeWidth: tw,
                                nodeHeight: th
                            };
                        },
                        setSelectors: function() {
                            _domNodes.nodes = $this.find(".dtree-node");
                            _domNodes.nodeContainers = $this.find(".dtree-node-main");
                            _domNodes.nodeCollapseToggles = $this.find(".node-collapse");
                            _domNodes.searchControl = $this.find(".dtree-search-control");
                            _domNodes.searchResults = $this.find(".dtree-search-list");

                            _domNodes.nodeCollapseToggles.on("click", handlers.onToggleCollapseClick);
                            _domNodes.searchControl.on("keyup", handlers.onSearchKeyup);
                            _domNodes.searchControl.on("blur", handlers.onSearchBlur);
                            $this.on("mousedown", handlers.onBoardMouseDown);
                            $this.on("mouseup", handlers.onBoardMouseUp);
                        },
                        buildSearchResults: function(listObj, skey) {
                            var listHTML = "";
                            for (var i = 0; i < listObj.length; i++) {
                                // var si = listObj[i].name.indexOf(skey) !== -1 ? listObj[i].name.indexOf(skey) : listObj[i].txt.indexOf(skey),
                                //     name = listObj[i]

                                listHTML += "<li>" + listObj[i].name + "</li>";
                            }

                            _domNodes.searchResults.html(listHTML);
                        }
                    },
                    handlers = {
                        onToggleCollapseClick: function(e) {
                            var $thisBtn = $(this),
                                $target = $($thisBtn.attr("data-dtree-collpase-node"));
                            $thisBtn.parent().toggleClass("dtree-collapsed");
                            $target.toggleClass("dtree-target-collapsed");
                        },
                        onSearchKeyup: function() {
                            var searchKey = $(this).val(),
                                resultSet = [];

                            resultSet = settings.nodes.filter(function(i, n) {
                                return (i.name.indexOf(searchKey) !== -1 || i.txt.indexOf(searchKey) !==
                                    -1);
                            });

                            init.buildSearchResults(resultSet, searchKey);
                        },
                        onSearchBlur: function() {
                            _domNodes.searchResults.html("");
                        },
                        onBoardMouseDown: function(e) {
                            _iniScale = e.offsetX;
                            $this.on("mousemove", handlers.onBoardMouseMove);
                        },
                        onBoardMouseUp: function(e) {
                            $this.off("mousemove", handlers.onBoardMouseMove);
                        },
                        onBoardMouseMove: function(e) {
                            e.preventDefault();
                            var pleft = _iniScale - e.offsetX;
                            $this.children().eq(0).scrollLeft($this.children().eq(0).scrollLeft() + pleft);
                        },
                        onScroll: function() {

                        },
                        onWheel: function(event) {
                            return;
                            event.preventDefault();
                            _iniScale = 0;
                            console.log(event.originalEvent);
                            if (!event.originalEvent.wheelDelta) {
                                if (event.deltaY < 0) {
                                    _iniScale += 0.08;
                                } else {
                                    _iniScale -= 0.05;
                                }
                            } else {
                                if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
                                    _iniScale + 0.08;
                                } else {
                                    _iniScale -= 0.05;
                                }
                            }

                            $this.children().eq(0).css("transform", "scale(" + _iniScale + ")");
                        }
                    },
                    calc = {
                        getParentChildObj: function() {
                            var nodes = settings.nodes,
                                keyNodeId = {},
                                parents = nodes.map(function(i, n) {
                                    var childNd = nodes.filter(function(k) {
                                        return k.pid == i.id
                                    });
                                    keyNodeId[i.id] = n;
                                    return {
                                        pid: i.id,
                                        pname: i.name,
                                        childNodes: childNd
                                    }
                                }).filter(function(i) {
                                    return i.childNodes.length
                                });

                            return {
                                parentObj: parents,
                                listParents: parents.map(function(i) {
                                    return i.pid
                                }),
                                rootNode: nodes.filter(function(i) {
                                    return i.pid == 0
                                })[0],
                                nodeById: keyNodeId
                            };
                        }
                    };

                init.logger();
                return $this;
            }
        }(jQuery));
        /* usage */
        $(document).ready(function() {

            var treeNodes = [{
                    id: 22,
                    pid: 71,
                    name: "Lexus Parisian",
                    txt: "DAD",
                    isCollapsed: true,
                    img: "/uploads/user/avatar/default-user-1.jpg"
                },
                {
                    id: 13,
                    pid: 22,
                    name: "Enid Goyette",
                    txt: "GRANDPA",
                    img: "/uploads/user/avatar/default-user.jpg"
                },
                {
                    id: 7,
                    pid: 79,
                    name: "jani",
                    txt: "GRANDMA",
                    img: "/uploads/user/avatar/default-user.jpg"
                },
                {
                    id: 71,
                    pid: 0,
                    name: "Jerod Borer",
                    txt: "YOU",
                    img: "/uploads/user/avatar/default-user.jpg"
                },
                {
                    id: 79,
                    pid: 71,
                    name: "Felicita Douglas",
                    txt: "MOM",
                    img: "/uploads/user/avatar/default-user-1.jpg"
                },
                {
                    id: 10,
                    pid: 71,
                    name: "",
                    txt: "SISTER",
                    img: "/uploads/user/avatar/default-user-1.jpg"
                },
                {
                    id: 11,
                    pid: 10,
                    name: "Enid Goyette",
                    txt: "ELDER SISTER",
                    img: "/uploads/user/avatar/default-user-1.jpg"
                },
                {
                    id: 12,
                    pid: 10,
                    name: "Madelyn Blanda",
                    txt: "YOUNGER SISTER",
                    img: "/uploads/user/avatar/default-user-1.jpg"
                },
                {
                    id: 24,
                    pid: 22,
                    name: "Yadira Schimmel",
                    txt: "GRANDMA",
                    img: "/uploads/user/avatar/default-user.jpg"
                },
                {
                    id: 26,
                    pid: 79,
                    name: "dany",
                    txt: "GRANDPA",
                    img: ""
                },
                {
                    id: 125,
                    pid: 4,
                    name: "eric",
                    txt: "Executive",
                    img: ""
                },
                {
                    id: 124,
                    pid: 4,
                    name: "t'challa",
                    txt: "Executive",
                    img: "/uploads/user/avatar/default-user.jpg"
                },
            ];
            $("#ochart").dtree({
                isHorizontal: false,
                nodes: treeNodes
            });
        });
    </script>
@endpush
