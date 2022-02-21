<template>
  <div :id="'canvas_wapper'+num" style="margin-bottom:0;max-height:400px">
    <canvas :id="'canvas'+num" width=400 height=400></canvas>
  </div>
</template>

<script>
module.exports = {
  props:{
    name:{
      type: String,
      default: null
    },
    color:{
      type: String,
      default: ''
    },
    title:{
      type: String,
      default: ''
    },
    rchart:{
      type: Array,
      default:[]
    }
  },
  data () {
    return {
      num: Math.floor( Math.random() * 100000 ),
      data: {
        labels: ['','',''],
        datasets: [{label:"評価値",
          data: [0,0,0],
          fill:true,
          backgroundColor:"rgba(0, 0, 0, 0.2)",
          borderColor:"rgb(0, 0, 0)",
          pointBackgroundColor:"rgb(255, 255, 255)",
          pointBorderColor:"#fff",
          pointHoverBackgroundColor:"#fff",
          pointHoverBorderColor:"rgb(255, 255, 255)"
        }]
      },
      option:{
        legend: {
                  display: false
              },
        title: {
                  display: false,
                  text: 'nameの評価'
              },
          scale: {
          angleLines: {
                  display: true
              },
              gridLines: {
                          display: true,
                      },
              ticks: {
                  max: 5,
                  min: 0,
                  stepSize: 1
              }
          },
        elements:{
          line:{
            tension:0,
            borderWidth:3
          }
        }
      }
    }
  },
  methods:{
    rgbColor(){
      switch (this.color) {
        case 'blue':
          return '54, 162, 232';
        case 'red':
          return '247, 133, 133';
        case 'orange':
          return '54, 162, 232';
        case 'gray':
          return '128, 128, 128';
        case 'green':
          return '76, 175, 80';
        case 'success':
          return '76, 175, 80';
        default:
          return '54, 162, 232'; //該当以外はblue
      }

    }
  },
  mounted () {
    this.option.title.text = this.name + 'の評価';
    this.data.labels = this.rchart.map(r => r.factor);
    this.data.datasets[0].data = this.rchart.map(r => r.value);
    this.data.datasets[0].backgroundColor = "rgba(" +this.rgbColor() + ", 0.2)";
    this.data.datasets[0].borderColor = "rgba(" +this.rgbColor() + ")";
    this.data.datasets[0].pointBackgroundColor = "rgba(" +this.rgbColor() + ")";
    this.data.datasets[0].pointHoverBorderColor = "rgba(" +this.rgbColor() + ")";

    var chartEl = document.getElementById("canvas"+this.num);
    var ctx = chartEl.getContext('2d');
    var chart = new Chart(ctx, {
      type: 'radar',
      data: this.data,
      options:this.options
    });

  }
}
</script>