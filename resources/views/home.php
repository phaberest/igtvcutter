<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ho cambiato vita IGTV downloader</title>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
</head>
<body class="is-full-screen container">
    <div class="hero is-full-screen is-center is-vertical-align" x-data="component()">
        <form id="subscribe" class="container" x-ref="form" accept-charset="UTF-8" action="/cut">
        <template x-if="!processing">    
            <div class="row">
                <div class="col">
                    <input type="text" required placeholder="Url di share IGTV" name="url" x-model="url">
                </div>
                <div class="col-3 is-vertical-align">
                    <button @click.prevent="submitForm($refs)">Dividi et impera</button>
                </div>
                </form>
            </div>
        </template>
        <template x-if="!processing">
            <div class="row">
                <div class="col">
                    <p>Incolla l'indirizzo di share della IGTV che devi spezzare, otterrai i 4 video tagliati che ti servono. Non bere troppo caffè mentre aspetti, meglio una tisana, che sennò ti agiti, sudi, ti viene la febbre e finisci a leggere i libri di Aranzulla.</p>
                </div>
            </div>
        </template>
        <template x-if="processing">
            <div class="row">
                <div class="col">
                    <div x-show="clips.length === 0">
                        <p>È incredibile come sia lento a passare il tempo quando fissi lo schermo così a lungo, ci hai mai pensato?</p>
                    </div>
                    <div x-show="clips.length === 1">
                        <p>E pensare che da bambini avevamo fretta che il tempo passasse, volevamo essere più grandi. Che poi, più grandi per fare gli astronauti. Poi abbiamo scoperto la scuola e...va beh.</p>
                    </div>
                    <div x-show="clips.length === 2">
                        <p>C'è sempre tempo eppure il tempo manca sempre...però è sempre tempo per una pizza. Senti, lo so, questa era particolarmente pessima, ma sono le 3 di notte e sto cercando di impegnare il tuo tempo.</p>
                    </div>
                    <div x-show="clips.length === 3">
                        <p>È in momenti come questo che penso alla vita e a quanti cagnolini possono nascere in una sola cucciolata. Lo sai che possono essere fino a 15? Non ne sono davvero sicuro in realtà.</p>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="processing">
            <div class="row">
                <div class="col-1">
                    <svg style="width: 40px;height: 40px;margin: 20px;display:inline-block;" version="1.1" id="L2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                    <circle fill="none" stroke="#000" stroke-width="4" stroke-miterlimit="10" cx="50" cy="50" r="48"/>
                    <line fill="none" stroke-linecap="round" stroke="#999" stroke-width="4" stroke-miterlimit="10" x1="50" y1="50" x2="85" y2="50.5">
                    <animateTransform 
                        attributeName="transform" 
                        dur="2s"
                        type="rotate"
                        from="0 50 50"
                        to="360 50 50"
                        repeatCount="indefinite" />
                    </line>
                    <line fill="none" stroke-linecap="round" stroke="#000" stroke-width="4" stroke-miterlimit="10" x1="50" y1="50" x2="49.5" y2="74">
                    <animateTransform 
                        attributeName="transform" 
                        dur="15s"
                        type="rotate"
                        from="0 50 50"
                        to="360 50 50"
                        repeatCount="indefinite" />
                    </line>
                    </svg>
                </div>
                <div class="col is-vertical-align">
                    <p>Sto elaborando, porta pazienza (sei su un raspberry sfigatissimo)</p>
                </div>
            </div>
        </template>
        <template x-if="success">
            <div class="row">
                <div class="col">
                    <p>Ripensandoci è meglio se dimentichiamo questa storia dei cuccioli.</p>
                </div>
            </div>
        </template>
        <template x-if="clips.length">
            <div class="row">
                <div class="col">    
                    <h2>Taglio e cucito pronto! (<span x-text="clips.length"></span>/4)</h2>

                    <ul>
                        <template x-for="(item, index) in clips">
                            <li>
                                <a :href="item" target="_blank" download x-text="`Split ${index + 1}`"></a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </template>
    </div>

    <script>
    function component () {
        return {
            url: '',
            clips: [],
            success: false,
            processing: false,
            submitForm() {
                this.clips = [];
                this.success = false;
                this.processing = true;
                fetch(this.$refs.form.action, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    method: 'post',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
                    body: `url=${this.url}&clip=0`
                })
                .then(response => response.json())
                .then(data => {
                    this.clips.push(data.clip);
                    fetch(this.$refs.form.action, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        method: 'post',
                        headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
                        body: `url=${this.url}&clip=1`
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.clips.push(data.clip);
                        fetch(this.$refs.form.action, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            method: 'post',
                            headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
                            body: `url=${this.url}&clip=2`
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.clips.push(data.clip);
                            fetch(this.$refs.form.action, {
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                method: 'post',
                                headers: {'Content-Type':'application/x-www-form-urlencoded'}, // this line is important, if this content-type is not set it wont work
                                body: `url=${this.url}&clip=3`
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.clips.push(data.clip);
                            })
                            .then(() => {
                                this.url = '';
                                this.success = true;
                                this.processing = false;
                            })
                        })
                    })
                })
            }
        }
    }
    </script>

    <!-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script> -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>