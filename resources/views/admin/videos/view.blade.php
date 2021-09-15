@extends('layouts.admin') 

@section('title', tr('view_live_videos_history')) 

@section('content-header') {{ tr('live_videos') }} 

@endsection 

@section('styles')

<style type="text/css">
    video {
        width: 100%;
    }
    
    .main_video_error {
        position: relative;
    }
    
    .flash_display {
        position: absolute;
        align-items: center;
        justify-content: center;
        display: flex;
        top: 0;
        bottom: 0;
        left: 0;
        background: #000;
        color: #fff;
        right: 0;
    }

    /**
    * Live stream css
    */    
    .media-container,
    .media-container * {
        margin: 0;
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
    
    .media-container,
    .media-container * {
        -moz-transition: all .5s ease-in-out;
        -ms-transition: all .5s ease-in-out;
        -o-transition: all .5s ease-in-out;
        -webkit-transition: all .5s ease-in-out;
        transition: all .5s ease-in-out;
        width: unset !important;
    }
    
    .media-box {
        background: black;
        border: 1px solid rgb(107, 107, 107);
        margin: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .media-controls,
    .volume-control {
        margin-top: 2px;
        position: absolute;
        margin-left: 5px;
        z-index: 100;
        opacity: 0;
    }
    
    .media-controls .control,
    .volume-control .control {
        width: 35px;
        height: 35px;
        background-position: center center;
        background-repeat: no-repeat;
        float: left;
        background-color: rgba(255, 255, 255, 0.84);
    }
    
    .media-controls .control:first-child {
        border-bottom-left-radius: 5px;
    }
    
    .volume-control .control:first-child {
        border-top-left-radius: 5px;
    }
    
    .media-controls .control:hover,
    .media-controls .selected,
    .volume-control .control:hover {
        background-color: rgba(255, 255, 255, 0.74);
    }
    
    .media-controls .control:active,
    .media-container .selected,
    .volume-control .control:active {
        background-color: rgba(255, 255, 255, 0.44)!important;
    }
    
    .mute-audio {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwgAADsIBFShKgAAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACsUlEQVRYR92Xu2siURTGp0hnJRgVGVNtEcHOiI2taWIa3Vgpgu4qiKBoCpOgxgdofD8QTFYUXMRSRLstt9xyy/0Tttxyi9kz17mXq7majFED+8EH+p2Zub+5j4NygiC8q/8PDQaDn9LHw6rf739Sq9UCthQfRk9PT2Rg7Ha7/U0q71erA2OXy+X9AgQ/B3KsgbFZADc3N25clyL5SiaTKnqgdW40Gs8A4vE4ARD9+Pj4XSq9Ts1m8xf9gE0uFosIYDwezyaTCXnjVqvVpq+T4oWur6/5UChEiuFweOkinL/GeAnoDD0ENJ1Of7ByTqFQ8HQB+/7+vi3WWbV1fnh4eAYgWsxEQb8gWSQS+YjCk5MTJkA2m5UNUK1WyR4olUq/cW6z2cJidn5+foSz09PTBYBer2cCpNNp2QC1Wm1pE9I1WIIz8BGAoe+xWEwYjUY8p9PpmAB3d3dbLwEWvOUHXHO73WdiZjAYrDiDE8JzWq2WCZBKpWQDwNstAXg8HgJgtVoRgN1uJwAul2v9DGwDsDoD0D8IALw5AnA6nQTg8vKS5zQazc72QKVSWQIIBAIEwGw2IwCHw0EALi4ueE6lUjEB8vn8m2fAYrEQgKurKwRgMpkIgN/v58UBmAC5XE42wOopgGeQGswOAlAqlQggGAwKnU6H546Pj5kA8DZvApjP56QPGI1G1Aeg65I+AHtv0QfWnQLo67IBYNkQQK/Xm9H5bDZDANBzCACs/wIApoIJgIogVm2dWa0Yms1X9CAQnUvRy6JvesmFQoEsgfhd+ohEXxeNRstS/DrRN29yt9td2oRY9DVer1fe7wEsn89npx/Ecr1e3wiQSCT+StH2ogdcdSaTYQLsXMPh8A8LgPWTbG+CM29cBZBKh9Xt7e0XOPfvM/hOxPrHekgzw8NZ4P4BtGizy4jmqy8AAAAASUVORK5CYII=');
    }
    
    .unmute-audio {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwgAADsIBFShKgAAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACY0lEQVRYR9WXS2/aQBDHfa9POaDGhKTpgTNIfICohfI4wC0HDuWdOCWQBKy24hmUBqduAjgVhx4Q50oIPk0/TQ/urhlbNplFEcWu+pd+EjszO7OyZweZ0zTtn4Ia3QQ1uslWNJ1OVVmWtU6n8xpM7ikSiVQ8Ho9mAGbnlcvlDq2FXT1AvV7/jRWnQIgzGg2GC6yoFQjdvsJv3ipYwVUgfHuKRt4dYYVYwLbnKxaL7WCJsu8zvHzbF8ST0ye+dUDa56vdbqOJvLsCrw5HwtXFJepnAWl1kdkgEU5gaUpRlGPCjb4gwwNNdHjwir9XvgnV8wrqZ6EnJQoGgynDlkgkjsDMpVKpgGEPBAIZTpIkWwID+gS+yndC+ewD6mcBdWhe8wCUeDzuTSaTL6y2QqGQ4brdrmmw4vPu8cOHgXBRqaJ+FlBfV7lclrAYSj6fX76CRqOBBhz49vnv6qNQu7xC/Sz0pBaRofVjNUYUxZ/g5rher2dzGuzv+XgyeDbuAavWxjSbTZvTgB6ANmGlfI76WUBaU1gMBdwc12q10ADh5S5/15eFs1MR9bOAtLpI99t6oFarmb+j0eiyB1gHoE04uH/YuAnT6bTtFhSLRS9pStstIFcywxxE9Ak8jtSNB1E4HDYPUCqVzDlQrVbNORAKhTLMJ0B74G9vwWKxkAhPJuF8Pj8mLF9BNpvdIdiSfP74SbvudPnbmy/O/xes02w2E/r9PlqIBWzdrsjgKGHFMGCLM8IKrgKhzmkymfzCChtAmPNi3R5wuyNyp6PW4n6/3/0PE6rxeKwSYrD8z4R9MLqHxv0BTZnWtpv+sYEAAAAASUVORK5CYII=');
    }
    
    .mute-video {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwQAADsEBuJFr7QAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAABoElEQVRYR2NgGAWDNARSge4qJxL7UN0PfHx8O5iZmW8CDSYGd1HdAXJycns4ODjuAQ3Ghz8D5f8D8SKqO0BfXz+Ll5e3FmgwPnyelg7gADqAE2gBPryEZg4gMkgXUtsBTEAD2UjAi6EOAIUEsj5GZA8A0xOTpKQkNnOZ0T0qCRRIJAEfhTrgGJoeQTQHSAMdgM1cY3QH2AAFXpOAf0AdAKKR9WmCDO7q6hIPCgrSEhISShQREcFmbg26AxyhBoKyFiVYF2RwW1tbrr+//zEWFpZrQIzNvDaaOsDGxqZdWVn5GyMj408cHqKNA9jY2EqApWg4Dw/PZk5OTnwhSTMHnGZiYjoA9PVjAlFJGweA4hsY7MSkIdo4gIQEPOqAYRoCwBxATAIEqaFNCADLgcdAR9wFWvBhQLIhOzt7K7AgygE6Yi+B0KBNCAAttQA6gNfAwGASsCqmf0kIDHZwZZSTk1Po4OBwBlgw3SK2MiK1OsZVdcOqYwNgdRzOzc3dyM/PT1R1rAJ0eB8VMKhhAwfAaNAANkiwmeuNXh2P8kdmCAAAkSPyEJegDaEAAAAASUVORK5CYII=');
    }
    
    .unmute-video {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwQAADsEBuJFr7QAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACFUlEQVRYR2NgGIFgPtDPGQPlb5Dl/4H4PRBL4HNEKlCynEjsQ6RvYJZ/B6p3wKuHj49vBzMz802gImJwFxEOaID6HGS5B0H1cnJyezg4OO4BFeLDn6GGLiJgIGmWgwzT19fP4uXlrQUy8eHzRDigAKoGFO8RBH0OUwB0AAfQAZxAPj68hIADEpAsB7GpDhbicQCy5RXE2swEVMhGAl4MdQAoJJD1JSL5vAGYnpgkJSWxmcuM7jBJoABIM7H4KNSiY0h6+oDsH1DxBpAFQAdIAx2AzUxjdAfYAAVek4BhFoFokL6PQPwPZnlXV5d4UFCQlpCQUKKIiAg2c2vQHeCIFHSgVEsungMyuK2tLdff3/8YCwvLNSDGZlYbrRygCzLYxsamXVlZ+RsjI+NPHJ6hjQPY2NhKgKVoOA8Pz2ZOTk58oUgzB5xmYmI6APT1YwLRSBsHgOIbGOzEpB/aOICExDvqgGEaAsAcQEwCBKmhTQgAy4HHQEfcBVrwYUCyITs7eyuwIMoBOmIvgdCgTQgALbUAOoDXwMBgErAmpH9JCAx2cF2Qk5NT6ODgcAZYMN0itjKCVceglivI5aCqFdR+J6WKBqnVBDkAWB0bAKvjcG5u7kZ+fn6iqmMVoL4rUMt/A2lQiwfUwCAVgxo2cACMBg1ggwSbGd7o1fF0qOXEtd3RdVPIbxixloMCDpZViO84UBjcyNoBhLMZ3JbarLEAAAAASUVORK5CYII=');
    }
    
    .record-audio {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwgAADsIBFShKgAAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACuUlEQVRYR72WTW8SURiFm9jquiGFhXahdWWl9bMWozQhIUaBahA/0spGKli1VC0EaqpSUIpWksoELcwaQrBCWLl073/xN4z3JXfIncuBIUZ8krOYM/e9z2SGCTOiaRqMGc1m81CtVsuUSqWMqqpTvO4L9KCS0o9qtXpgtVo1Ofx0T6AHlZReZDKZXSTX4/f7J/nSLqAHlRREOByeRlI5fHkX0INKCsLn891EQjnFYvEaHzEAPaikmCFL8/m86RD0oJKCmJubs8tiOewuKfF4fIaPGIAeVFIQTDDQIwiFQrf4iAHoQSWlH+y9V2RpIpHoP8SAHlRSEOwtOBOJRFRZricQCKjJZFItl8tn+YgB6EElBWGz2QZ6BMFgcDiPYNY+cwkJ5axGHs/zEQPQg0qKGbK0sPfZdAh6UElBeK7f8MlilHRqe5GPGIAeVFIQk0ePDfQbeLC0PJzfwMkTUwNdwNPVJ//mAjwez7K+Kfv//22fPj3QBWwmkovicaVSWfqrC0ilUpfFje7fvXdOPO6V798OFsTj9mYM6EElRUfcqNVqbd25Hej7PXDx/IVT0WdrTf34xfrzfb4V9qCSopNOp/dEQfbd+7H4Ruyn2Omh9QtXnWNypwM9qKSIiBsyeZ3d4olXyc3R2MsN9VFoRf2wk1O/Fr+MrjwMTYhr19eiab5FG+hBJUVEUZR5ceM3W69/8VMGxDXbb1PGTRjQg0qKjNPpHBcFlJ1sVivvlzR2Jww95dPH3XE+2gF6UElBsNfS9IPE6/UqhUJhOB8kRC6XO+5yua6wV7RL7na7Z/kyCPSgkmKGw+E4LMotFssRfqon0INKigz76Iyxr55OotFoQrwAFD7aAXpQSZFBArM0Go06H28DPaikyCCBWer1+g8+3gZ6UEmRQQKz8NEO0INKyjCAHlT+v2gjfwDNQh1izdJWJQAAAABJRU5ErkJggg==');
    }
    
    .record-video {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwAAADsABataJCQAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACEElEQVRYR+2XvUscQRiHT89vSJEgWgSDBARTJIUELBQ8LZNaLCyCmCqICKZJ5T8QBSsr4UBE7QQFIW3wE2KKgEjiB1goCFEEEfxIzucnMzIst3vnMbjNLTzs7Ozs+/7mnXc+NpHJZBJxEqtzdfxeQOIRLzfiRQHFCLgRmCAPn2fJxVfUrfkkLAkvcbIEzQERb3lmvvgjTIB18hNnHVBmhDy6AAk5gn54CrEIkIgLGIVPPsMvW7mGIDjeh3EL8JqAhUSgKCCfCPwntCeQhm5ohw8wD+eg9/d2HpqEuQT8w/gmvAwsYPbxPYXfrgjfAjYw/ibEua2uMtP6rjMPFRA1DU8xWAslOQTodSVIbN4CtBCNQdRCtML7UiNgins23L1lOF8BWoo/Qq6leNrp+R830Zxyn9NmIR8B2oxSUG4+jNoL0rSpMRFo4L4bEDHLs8ZfVxLWowQUsh1/w2CT08NWysr4K5gDDY+9XlPYjhKgA8kz5wNb1IFEyl1+8KwEVIK+MxGw7bWVfwUbQdv7zxTO4NjHqfgJhsbhBmagMYtwW6XQKzLf4RqGfAjQtJNR5YvCPQkvQkS0UL9onGvI6nwIkK9q6IV9I0LHuUFoAw1bJ3yBZeNc58oUJH0JkAjNgB44AK33f2EHtmAPNOZaqlehCyr0kU8Bsqezo6bgCASn4S/qBqBePVfjUAFx/SHH/nd8C3srt2KeTS1mAAAAAElFTkSuQmCC');
    }
    
    .stop-recording-audio {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwgAADsIBFShKgAAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAADAUlEQVRYR7WWS08TURiGhxTULSGUhZKouBILXhGMFkNCjJaiwXoJlI1FKiqlSpteglpahSqSYJsq7awhTUUaVizd+xtcuDQmxkTdeRnP15xpzhzedhoiT/IkzDvnO+9kphNG0TQNakaxWLTk8/lENptNqKraxuOqwB4UktVYXV1ds1qtmiw/XRHYg0KyEolEYgGV6w4NDbXypVuAPSgkEePj4+2oVJYv3wLsQSGJcDqdl1GhbCaTucBHDMAeFJJmyKWLi4umQ7AHhSSiq6vLJhfLsruUDgaDHXzEAOxBIYlgBTU9Ao/Hc4WPGIA9KCSrwd77tFwaCoWqDzFgDwpJBHsLjnq9XlUu13W5XGo4HFZzudwxPmIA9qCQRLS0tNT0CEZHR3fmEXTaOk6jQtkJ751uPmIA9qCQNEMuTS29Mh2CPSgkEY6Ll5xyMTIemx3kIwZgDwpJROvefTX9BtzDIzvzGzh0sK2mC7g3cff/XIDD4RjRN2X//7/a2o/UdAGRUHhQPF5ZWRne1gXEYrEz4kY3r984Lh5X8t3btV7xuLQZA/agkNQRN9rY2Ji5dtVV9Xvg1ImTh333J4v68YMp/zLfCvegkNSJx+NLYsHc02cNwenAezHTpfW95+wNcqYDe1BIiogbsvICu8XN0XCkPvBwWr3tGVOfzyfVN5nX9WO3PM3i2qlJX5xvUQL2oJAUSafT3eLGj2cefeCnDIhrZp/EjJswYA8KSRm73d4oFpDzc3NabjmrsTthyMmXLxYa+WgZ2INCEsFeS9MPkoGBgXQqldqZDxIimUwe6OvrO8teUUNxNBrV+vv7O/kyCOxBIWlGT0/PLvECmpqadvNTFYE9KCRl2EdngH31lPX5fCHxAtxudygSiQT8fn/pPHt9A3y0DOxBISkjltXq+vp6gY+XgD0oJGVQgZmFQmGTj5eAPSgkZb7X1f0oLTfxo8XySZePloE9KCRF2NH+P4rymfnrt6L8/Kso39jfX/TSSvLxMrAHhdtWUfYwz5dFawxqyj+MRz2Y+XGHbwAAAABJRU5ErkJggg==');
    }
    
    .stop-recording-video {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwAAADsABataJCQAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAACAElEQVRYR+2XvUscQRiHT42aBCwU0SIoQRBioUUQLBROLWMtKSxEtBKRQGys/AsUrKwC14itoCDYip+gFoJIiAoWCoKKIIJfOZ+fzBzDcbv3weA2d/DA7M7uO8+887FzsWQyGYuSSBtXx1MCsXf8uRkvChQz4GZgjnn4JcNcbOHelk+CJuEDjazAtzSJdq5ZL/4IErCN7NNYHD4YkXcXkMgFDEM1RCIgiXuYhlGf6VesbEOQPt7nUQt4nYCFZKAokEsG/pPaa0hAP3TBICzCHag+FSffSZhN4IXge9CUtoHZyz4Kf10J3wI7BG8LaNze/miW9Vtn8hUIW4Y3BKyFkiwCqq4EyeYsoI1oBsI2og3qSyXwdrjKAHXut+V3rgLaikcg21Y8b3seIjDkZGcpFwF9jLqh3LwY9i1I8MxnMwQNARnQ+OtXBtthAoV8jlcJ2Oz0sMOVMMNjq1spHIUJ6EBS4wSzRR1IZO6yy7UmoCboD5MB+3xcEk4Gbe8nKNzCpY9TcRWBZuEZFuBrBnF7S6nvgDV4gl8+BLTsFFTz5RH+QGOAxHfuL5vGNWR1PgTU1icYgFMjoePcOHSChq0HJmHdNK5zZTeU+RKQhFbATzgD7fdX8A8O4QQ05tqqN6EXKvSSTwHF09mxAabgWPEdDiiPQb16rocDBaL6hxz5v+NXmoPQBeoNXQgAAAAASUVORK5CYII=');
    }
    
    .stop {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAOwgAADsIBFShKgAAAABp0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjUuMTFH80I3AAAEzUlEQVRYR8VXWUhtZRS+Hud5xAk9TsccUBHRFNFMQUUCLRUjwUBxDpQjCE744AReE3FMSQ3SNBNyfOmpxx6CiKCX6EKjdIsLNyoiClbr+9n78J999j57Sxfa8HHYe69/rW+v/1vrX+cBET34PyGCW7x82M6X4c8IZARpgGcBDD8GbE0vEdsCAQQNYxQxRhkfMh4x/mSAPfA34zHjY8Y8o4YRqZA1JGKFAAK/wNiy2Wx3YWFhlJycTFlZWZSbm0t5eXkuZGdnU2pqKkVHR5O/v/8Thegr/BujZM6DiDcCNraOZ7zh4+PzNZyWl5fT+Pg4r/GumZWVFWpqaqKkpCQQuWMfa0r2sHVulxEBBM9kPAwMDPyloKCAZmZmTANrie3u7lJtbS2Fh4dji64YFdot0SMA8djBOiQk5Pf6+nq6uLi4d3CZTE9PD8XExKgknpdJ6BGAcGaCg4N/RRrN0m31/fDwsNAG+z5nZDFElWgJoITa/fz8vsd+W3Vu1a6zs5M4q39xjFlGiB6BWH74EcSD/dNzPDk5Sefn56bk1tbWdG1KSkqIq+k7jvMiwyZnAMLrZNH91tbWpru4q6uLoqKiKD8/n05PT3Vtbm9vqbKyUlTA2NiYh83CwoLwwbH2oAWZAErkg4SEBDo5OfFYCGeKmkXjycnJocvLSze76+trKi4uJl9fX2ETFxdHi4uLHr6KioqIS/tntkmRCdg5NU+N9v7s7Ew0HDhWgcajbtPNzY1oTOzY9T4zM5MODg48CAwMDBBnGnavygRa8XBwcNBwf4+OjsjhcLiRQBCkNS0tzS14RkYG7ezs6PqCn4iICPh5SyawiDa7tbXlVWCHh4eUkpLiRiI0NNTtPj09nfb39736iY2NxZpPZALHkZGRlpoO0pqYmOgWVN0WnAV6addWVHx8PNZ/KxN4H+qEkKzUdW9vr7qPLiJYv7S0ZGm9QuAHmcB7yIBW2Xpk9vb2xKknCw4ZgPqrq6vvQ+AbmcBDlBmce8vA9va2OI7lauDu5rrnLko1NTWmJFCi7ONTmcBrQUFBNDIyYrgY3Q1fLgdHPxgaGiJFVOIdH8HU2Nho6AcljWyzLWrUNRE5uA8YpnBjY0OUmhy8sLDQFWR+fp7QxNT3KOnm5mZdEqOjo4SPZdvXZQKY7x6hhV5dXek2D7XcQLSsrMzDRksCQtM7E7CWffzE8RwyAcx9TjBDz9fTQUtLC6FXeBPa7OysyATO/76+Pg8/6+vr6na9qz0LcDpmML5Eqo+Pj3VJTExMmApseXmZjOyqqqqIhfojx2nQnoYggLG6KyAg4HFdXZ1pICv9Qrbp7+9XDzTMiBh2PQYSPItmvIn97ujoeGYkpqamSGk+n7H/Uny9EQG8eI7xDvpCa2vrfybhdDpF6+bG9QX7fZkBwYtLFqH6DL8QZB7jkGfDfyoqKggN6L4pR1uHcJUB5HP2h/8IwXIgIwIqCYhyjkVzh0bT0NBAm5ubpkTQaDD/2e12nBd/sA8MotXyl5tlQH2PyTWU8RLjmok8RRmi+7W3t9P09DStrq4KzM3NUXd3N5WWlorpl4WMRvMVY5iRyMD/RY/LWwZkY+gCFVLHeJvxBM0Ihw96vwo8Uw4oDJ1OJTC20/ByEbjv/j5L+38BGxuYOLu/9/8AAAAASUVORK5CYII=');
    }
    
    .take-snapshot {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAA8dJREFUWEftVktIW0EU1aq0BUGrYuL/WxF/UasmSm0SBbVqVCrGX0T8VFBpLSrBLxIjKkb8oAtR9xWhIAgtLXRfWorgqqtC/XTTLnTTRW2dnnnOG98zkdTfosULh7w3c+ee827u3BmXa/svbXp62rWrq+tmSkrKncbGxqTm5uZ7paWl3jExMa7M5fJtYWHBY3R09K7BYNCB6JFOp7PExsZu+vv7EwqFQjETGBioYO4Xs9XVVW98oSIxMVHZ3d1dAQJzWFjYEEjfpqam7iclJZGWlhbS09ND9Hq9IIDhPgtxdltfX88YGxurioqKmgsPD18D4UulUvkqOTn5q4SAJCQkkKmpKQJzoaivr+dzJSUlQxaLxYuFdG4TExO38aWtWVlZ70G4FRER8Q2kP2kwKYlIwEj4+OzsLIFYPhcSErKH91gW/tjm5+fd5ubmnthstscoliCkcZkuAJkA/H88CEVvby8nWVxclM2VlZUJc0tLSyQvL4+OHQK/GQ6AOEZ7bDU1NW7FxcV9mPwcHBxsQvHsNDU1cZKKigoZiThOIR2nwPrD6Ojo73jegPANfIAtLS3NaDabjZOTk36MUm61tbVuqF4qgAbYlRJQpKeny0hGRkbI+Pg4qaqqEt5BcoCC/IjnZZAtDgwMNMfFxXli+3larVYPRnO6SQVQYP+C94icVrM4LkVkZOQaauM5is4SFBTU2tfXl8HCnd3q6urcUDxcAAVNOxVAf3NzcwlqYwZF9BCFmI/9rNdoNMGoaOXKysotFub8JgpQqVT8ywMCAghSSNra2gh9Rpp/QNg+w94FoWTUR1ZdXS0UoVQAnHg2sPdJaGgof6cQ/U765ufn87nh4WG69WTrGAIZ9ZHRDKAGnjlwFGAymYTOhhbLx0QSCqkvtrBsrry8nM9JIBdQWVnpWlhYmIzKfYEtSCCGO2dnZxP832TEaiUdTzto8QnjDQ0NBE2KdHZ2cl8RarVamGtvbycatcZuHpALoIY0+6JLfUCRkczMTO6sQl+Pj48XRDyAmFNSelbYCzAajT4FBQXvHDhfBewFoNJR6MpfopNWq5X9lxeBTqtzLqCoqMgXR+eG6GQoNmCt44BnBWI7F4DBgBNOV4l/SEBOTg6y6Di1zoAbkl08hr8XIAbb+rJFtoGdrW2n2N3e4escxQSua8BeAA4jP2xDfo2+KqBNb6LH2N+McIdz7+/v16L3v3a08DxA297EDeuT+I7b0htc9XS4MbkzWrkNDg7eQEv2grPyMoBzxQe3aV/xHUe1F07VG4zu2mAuLn8AJ5n+SnkR0KgAAAAASUVORK5CYII=');
    }
    
    .zoom-in {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAAe1JREFUWEftl8tqwkAUhn2DPoV3vEaNikENaHAruhLUbUC87FwIbly5cKGbgNBdu3GbF+kTTf2HOSVTx7SL0Aj1wE+mkzP/+RzN5DQSZjDGxCikkACi0SjLZrO+isfjvsj7/f4ln8+zTCajXE9CLeRLAJqmsWKxyIUxyft3qVTyBdjtdma5XGaFQkHygMgbSiQS/gAQimEOVxLMRboyDoeDqeu6tI7G5I8dGgwGagBKzOVyH4vFYrNarSRdt3gj0u/Ger3eLJdLaR28KpUK9x4OhyydTqsBCMJxnDcxHVgYhuH2+32+C6lU6u8BRqORC+/QAEzT5ACoEQpAq9VyqcZjAuC7wWOGx+hyuThiOrCYz+fv8IdisdgtQBjxBHgCPAEeCwAHBL2vT6dT4Cdhr9fjJyFO2uvrnleWAOgmII7HY+AAjUbDhTeUTCbVAATxP19GuIHtAcD5fA4coNls/twPAAA/xmtzGThAu93+3Q5AaKun0ymbzWZsMpnwMVSr1V5F+t0Yj8df+SR4UPG7ALT9SEAHa1kWfyS9PT7ui3Rl0P8F8KI1JJrDVQmAhSiGK4p7F5EAJtKVsd1uTW8+BA+vD8bKthxFq9Uq63a7/JOiGAlQUKfT8QVA1Ot13lXRmu+Ct23btwBhxAMAsMgnlwgSabRVBN4AAAAASUVORK5CYII=');
    }
    
    .zoom-out {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjExR/NCNwAAAglJREFUWEftV8lqAkEQzYd4EuK47ytueHA5OPgFjoIHEVzwYOIl4NWf8JKDl/xXPqPSr+nSUceZEYfEgA8Kpal686yqqWpf/gWCwSAlEgmKxWK02+3e1LEl4BsOh6W/OrofmqZRKpUiXdepXq9/qGNLpNNpgmWzWRoMBpo6vg+RSIS63a4krVQqtgKSySRlMhlvBdRqNcrn85K0XC7/vgCQ5nI5mVqnEsAX5YKAfr/vjQA0Hwjxy9wI4B7wTABn4BYB8C+VSu4FiDrrSB0eIuosDWR4nXAGUjcCuFnhz0IQZzbRyJev6Hg8lrUrFAqEpuNaMhEHu3kNO52O9OVYsyAYZoVyPwIC0OmNRkMGczbMRG4EcAO2Wq1DDMezRaPRSwGr1cq32WxoOp3SbDaztPl87jjdDMP4WiwWtFwuEfN9zgETz/pU7k888cQDYb1e+8SEMuwsFAoZyv0qxCS1jDVbr9e75JlMJofJd82wF5zuAzz97AwXHOV+BEaxlTOPT/5erVZtBZzHmj/ZLEex2ANyG55bPB4/IXCzjhGHfcCrGXFmzmKx6DjST4DUM5FbAfAX5fLmQoKa3ZIB+CEDYjE9BXgjQLy7khR1bbfbtgLQL7jYwH84HHojIBAIHDq72Ww6ZgB+EDAajbwR4Pf7pQCQbrfbd3VsCfwv5Gzt9/tXdfzAIKI/NHr5AU4kDfWD0WSsAAAAAElFTkSuQmCC');
    }
    
    .media-box video {
        width: 100% !important;
        vertical-align: top;
        max-height: 460px !important;
        /*object-fit: fill;*/
    }
    
    .media-box audio {
        height: 5em;
    }
    
    .volume-control .volume-slider,
    .media-controls .volume-slider {
        width: auto;
        background: rgba(255, 255, 255, 0.09)!important;
        border: 1px solid white;
        height: 33px;
    }
    
    .volume-control .volume-slider input[type=range],
    .media-controls .volume-slider input[type=range] {
        margin-top: 9px;
        height: 15px;
        outline: none;
    }
</style>
@endsection 

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.videos.index')}}"><i class="fa fa-video-camera"></i> {{tr('live_videos')}}</a></li>
    <li class="active"><i class="fa fa-video-o"></i> {{tr('view_video')}}</li>
@endsection 

@section('content') 

@include('notification.notify')

<div class="row">

    <div class="col-xs-12">

        <div class="panel">

            <div class="panel-body">

                <div class="post">
                    <div class="user-block">

                        <img class="img-circle img-bordered-sm" src="{{$data->user ?  $data->user->picture : asset('placeholder.png') }}" alt="User Image">

                        <span class="username">
                                <a href="{{$data->user ? route('admin.users.view', ['user_id' => $data->user->id]): '#' }}">{{$data->user ? $data->user->name : tr('user_not_available')}}</a>                               
                            </span>

                        <span class="description">{{$data->created_at ? $data->created_at->diffForHumans() : '-'}}</span>

                    </div>

                    <div class="row margin-bottom">

                        <div class="col-sm-6">

                            @if(!$data->is_streaming || $data->status)

                            <img class="img-responsive" src="{{$data->snapshot}}" alt="Photo"> 

                            @else

                            <div ng-app="liveApp">

                                <div ng-controller="streamCtrl">

                                    <div class="show-model">

                                        <div id="videos-container">

                                            <div id="loader_btn" style="margin-left: 30%">
                                                <p>{{tr('video_loading')}}</p>
                                            </div>

                                        </div>

                                        <div class="main_video_error live_img" id="main_video_setup_error" style="display: none;">
                                            <img src="{{asset('error.jpg')}}" class="error-image" alt="Error" style="width: 100%;height: 250px;">

                                            <div class="flash_display" id="flash_error_display" style="display: none;">
                                                <div class="flash_error_div">
                                                    <div class="flash_error">{{tr('flash_missing_error')}}<a target="_blank" href="http://get.adobe.com/flashplayer/" class="underline"> {{tr('adobe')}}</a>.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            @endif
                        </div>

                        <div class="col-sm-3">
                            <div class="row">
                                <div class="header">

                                    <h3><b>{{tr('title')}}</b></h3>

                                    <h4>{{$data->title}}</h4>

                                </div>
                                <div class="header">

                                    <h4><b>{{tr('payment')}}</b></h4> @if($data->payment_status)
                                    <label class="text-red">{{tr('payment')}}</label>
                                    @else
                                    <label class="text-yellow">{{tr('free')}}</label>
                                    @endif

                                </div>

                                <div class="header">

                                    <h4><b>{{tr('is_streaming')}}</b></h4> 

                                    @if($data->is_streaming) 

                                        @if(!$data->status)
                                        <label class="text-green"><b>{{tr('video_call_started')}}</b></label>
                                        @else
                                        <label class="text-green"><b>{{tr('video_call_ended')}}</b></label>
                                        @endif 

                                    @else

                                    <label class="text-navyblue"><b>{{tr('video_call_initiated')}}</b></label>

                                    @endif

                                </div>

                                <div class="header">
                                    <h4><b>{{tr('browser_name')}}</b></h4>
                                    <b> <b>{{$data->browser_name ? $data->browser_name : tr('not_available')}}</b></b>
                                </div>
                                <div class="header">

                                    <h4><b>{{tr('video_type')}}</b></h4> @if($data->type == TYPE_PUBLIC)

                                    <label class="text-green"><b>{{TYPE_PUBLIC}}</b></label>

                                    @else
                                    <label class="text-navyblue"><b>{{TYPE_PRIVATE}}</b></label>
                                    @endif
                                </div>
                                <h4><b>{{tr('description')}}</b></h4>
                                <b>{{$data->description}}</b>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="header">
                                <h4><b>{{tr('viewers_count')}}</b></h4>
                                <b>{{$data->viewer_cnt ? $data->viewer_cnt : "0"}}</b>
                            </div>
                            <div class="header">
                                <h4><b>{{tr('start_time')}}</b></h4>
                                <b>{{$data->start_time ? date('g:i A', strtotime($data->start_time)) :'-'}}</b>
                            </div>
                            <div class="header">
                                <h4><b>{{tr('end_time')}}</b></h4>
                                <b>{{$data->end_time ?date('g:i A', strtotime($data->end_time)) :'-'}}</b>
                            </div>
                            <div class="header">
                                <h4><b>{{tr('no_of_minuts')}}</b></h4>
                                <b>{{$data->no_of_minutes}}</b>
                            </div>
                            <div class="header">
                                <h4><b>{{tr('created_at')}}</b></h4>
                                <b>{{common_date($data->created_at,Auth::guard('admin')->user()->timezone)}}</b>
                            </div>
                            <div class="header">
                                <h4><b>{{tr('updated_at')}}</b></h4>
                                <b>{{common_date($data->updated_at,Auth::guard('admin')->user()->timezone)}}</b>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            @if ($data->amount > 0)
                            <div class=" box box-widget widget-user-2">

                                <div class="widget-user-header bg-green">

                                    <h4 class="video_amount_heading">{{tr('payment_details')}}</h4>

                                </div>

                                <div class="box-footer no-padding">

                                    <ul class="nav nav-stacked">

                                        <li>
                                            <a href="#">{{tr('video_amount')}} 
                                            <span class="pull-right">
                                                {{formatted_amount($data->amount)}}
                                            </span>
                                            </a>
                                        </li>

                                        <div class="clearfix"></div>

                                        <?php $commission_split = each_video_payment($data->id);?>

                                            <li>
                                                <a href="#">{{tr('total_amount')}} 
                                                    <span class="pull-right">
                                                          {{formatted_amount($commission_split['user_amount'])}}
                                                    </span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">{{tr('user_commission')}} 
                                                    <span class="pull-right">
                                                         {{formatted_amount($commission_split['user_amount'])}}
                                                    </span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#">{{tr('admin_commission')}} 
                                                    <span class="pull-right">
                                                        {{formatted_amount($commission_split['admin_amount'])}}
                                                    </span>
                                                </a>
                                            </li>
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@if ($data->is_streaming && !$data->status) 

    @section('scripts')

        <!-- <script src="{{asset('common/js/jQuery.js')}}"></script> -->
        <script src="{{asset('lib/angular/angular.min.js')}}"></script>
        <script src="{{asset('lib/angular-socket-io/socket.min.js')}}"></script>
        <script src="{{asset('lib/socketio/socket.io-1.4.5.js')}}"></script>
        <script src="{{asset('lib/rtc-multi-connection/RTCMultiConnection.js')}}"></script>
        <script src="{{asset('common/js/getHTMLMediaElement.js')}}"></script>
        <script src="{{asset('jwplayer/jwplayer.js')}}"></script>

        <script>
            jwplayer.key = "{{Setting::get('jwplayer_key')}}";
        </script>

        @if(!$data->video_url)
            <script type="text/javascript">
                var url = "<?= url('/');?>";

                var appSettings = <?= json_encode([
                            'SOCKET_URL' => Setting::get('SOCKET_URL'),
                            'CHAT_ROOM_ID' => isset($data) ? $data->id : null,
                            'BASE_URL' => Setting::get('BASE_URL'),
                            'TURN_CONFIG' => [],
                            'TOKEN' => null,
                            'USER' => null,
                        ]); ?>;

                var socket_url = "<?= Setting::get('SOCKET_URL');?>";

                var video_details = <?= $data; ?>;

                var routeUrl = "<?= route('admin.videos.index') ?>";

                var liveAppCtrl = angular.module('liveApp', [
                        'btford.socket-io',
                    ], function($interpolateProvider) {
                        $interpolateProvider.startSymbol('<%');
                        $interpolateProvider.endSymbol('%>');
                    })
                    .constant('appSettings', appSettings)
                    .constant('url', url)
                    .constant('routeUrl', routeUrl);
                liveAppCtrl
                    .run(['$rootScope',
                        '$window',
                        '$timeout',
                        function($rootScope, $window, $timeout) {

                            $rootScope.appSettings = appSettings;

                            $rootScope.videoDetails = video_details;

                            console.log($rootScope.videoDetails);
                        }
                    ]);
                console.log(appSettings);
            </script>
            <!-- <script src="{{asset('common/js/factory.js')}}"></script> -->
            <script src="{{asset('common/js/streamController.js')}}"></script>
        @else

            <script type="text/javascript">
                var is_mobile = false;

                var isMobile = {
                    Android: function() {
                        return navigator.userAgent.match(/Android/i);
                    },
                    BlackBerry: function() {
                        return navigator.userAgent.match(/BlackBerry/i);
                    },
                    iOS: function() {
                        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                    },
                    Opera: function() {
                        return navigator.userAgent.match(/Opera Mini/i);
                    },
                    Windows: function() {
                        return navigator.userAgent.match(/IEMobile/i);
                    },
                    any: function() {
                        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                    }
                };

                function getBrowser() {

                    // Opera 8.0+
                    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

                    // Firefox 1.0+
                    var isFirefox = typeof InstallTrigger !== 'undefined';

                    // Safari 3.0+ "[object HTMLElementConstructor]" 
                    var isSafari = /constructor/i.test(window.HTMLElement) || (function(p) {
                        return p.toString() === "[object SafariRemoteNotification]";
                    })(!window['safari'] || safari.pushNotification);

                    // Internet Explorer 6-11
                    var isIE = /*@cc_on!@*/ false || !!document.documentMode;

                    // Edge 20+
                    var isEdge = !isIE && !!window.StyleMedia;

                    // Chrome 1+
                    var isChrome = !!window.chrome && !!window.chrome.webstore;

                    // Blink engine detection
                    var isBlink = (isChrome || isOpera) && !!window.CSS;

                    var b_n = '';

                    switch (true) {

                        case isFirefox:
                            _n = "Firefox";
                            reak;

                        case isChrome:
                            b_n = "Chrome";
                            break;

                        case isSafari:
                            b_n = "Safari";
                            break;

                        case isOpera:
                            b_n = "Opera";
                            break;

                        case isIE:
                            b_n = "IE";
                            break;

                        case isEdge:
                            b_n = "Edge";
                            break;

                        case isBlink:
                            b_n = "Blink";
                            break;

                        default:
                            b_n = "Unknown";
                            break;
                    }

                    return b_n;
                }

                if (isMobile.any()) {
                    var is_mobile = true;
                }

                var browser = getBrowser();

                if ((browser == 'Safari') || (browser == 'Opera') || isMobile.iOS()) {

                    var video_url = "{{$ios_video_url}}";

                } else {

                    var video_url = "{{$data->video_url}}";

                }

                console.log(video_url);

                var playerInstance = jwplayer("videos-container");

                playerInstance.setup({
                    file: video_url,
                    image: "{{$data->snapshot}}",
                    width: "100%",
                    aspectratio: "16:9",
                    primary: "flash5",
                    controls: true,
                    "controlbar.idlehide": false,
                    controlBarMode: 'floating',
                    autostart: true,
                });
                playerInstance.on('setupError', function(e) {

                    console.log(e);

                    jQuery('#videos-container').hide();

                    var hasFlash = false;
                    try {
                        var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
                        if (fo) {
                            hasFlash = true;
                        }
                    } catch (e) {
                        if (navigator.mimeTypes && navigator.mimeTypes['application/x-shockwave-flash'] != undefined && navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin) {
                            hasFlash = true;
                        }
                    }

                    if (hasFlash == false) {

                        console.log("eerr");

                        $("#flash_error_display").show();

                        $('#main_video_setup_error').show();

                        // console.log('<a target="_blank" href="https://get.adobe.com/flashplayer/" class="underline">adobe</a>');

                        return false;
                    }
                    $('#main_video_setup_error').show();

                    // $('#main_video_setup_error').show();    

                });
            </script>
        @endif 

    @endsection 

@endif