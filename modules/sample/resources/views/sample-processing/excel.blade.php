<div>
    <table>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;"><b>{{ factoryName() }}</b></td>
        </tr>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;"><b>{{ factoryAddress() }}</b></td>
        </tr>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;">Sample Processing</td>
        </tr>
    </table>
</div>
@includeIf('sample::sample-processing.details')
